<?php

/**
 * ============================================================================
 * APP AUTO - Controller: ClientWalletController
 * ============================================================================
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Wallet;
use App\Models\Vehicle;

class ClientWalletController extends Controller
{
    protected $walletModel;
    protected $vehicleModel;
    protected $uploadPath;

    public function __construct()
    {
        $this->walletModel = new Wallet();
        $this->vehicleModel = new Vehicle();
        $this->uploadPath = APP_PATH . '/public/uploads/documentos/';
        
        // Criar diretório se não existir
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }
    }

    /**
     * Listar documentos da carteira
     */
    public function index()
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar veículos do usuário
        $vehicles = $this->vehicleModel->findByUserId($userId);

        // Buscar documentos agrupados por veículo
        $documentsByVehicle = [];
        foreach ($vehicles as $vehicle) {
            $documents = $this->walletModel->findByVehicleId($vehicle['id']);
            if (!empty($documents)) {
                $documentsByVehicle[$vehicle['id']] = [
                    'vehicle' => $vehicle,
                    'documents' => $documents,
                ];
            }
        }

        // Buscar alertas de vencimento
        $alerts = $this->getExpiryAlerts($userId);

        $this->view('cliente/carteira/index', [
            'user' => $user,
            'vehicles' => $vehicles,
            'documentsByVehicle' => $documentsByVehicle,
            'alerts' => $alerts,
        ]);
    }

    /**
     * Upload de documento
     */
    public function upload()
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        // Validar CSRF
        if (!$this->validateCsrf()) {
            $this->json(['sucesso' => false, 'mensagem' => 'Token CSRF inválido'], 403);
        }

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Validar entrada
        $vehicleId = $this->sanitize($_POST['veiculo_id'] ?? '');
        $tipoDocumento = $this->sanitize($_POST['tipo_documento'] ?? '');
        $dataVencimento = $this->sanitize($_POST['data_vencimento'] ?? '');
        $observacoes = $this->sanitize($_POST['observacoes'] ?? '');

        if (!$vehicleId || !$tipoDocumento) {
            $this->json(['sucesso' => false, 'mensagem' => 'Campos obrigatórios não preenchidos'], 422);
        }

        // Verificar se o veículo pertence ao usuário
        $vehicle = $this->vehicleModel->findById($vehicleId);
        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            $this->json(['sucesso' => false, 'mensagem' => 'Veículo não encontrado'], 404);
        }

        // Validar arquivo
        if (!isset($_FILES['arquivo']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao fazer upload do arquivo'], 400);
        }

        $file = $_FILES['arquivo'];
        
        // Validar tipo de arquivo
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        if (!in_array($file['type'], $allowedTypes)) {
            $this->json(['sucesso' => false, 'mensagem' => 'Tipo de arquivo não permitido. Use JPG, PNG ou PDF'], 400);
        }

        // Validar tamanho (max 5MB)
        $maxSize = 5 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            $this->json(['sucesso' => false, 'mensagem' => 'Arquivo muito grande. Tamanho máximo: 5MB'], 400);
        }

        // Gerar nome único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('doc_') . '_' . time() . '.' . $extension;
        $filePath = $this->uploadPath . $fileName;

        // Mover arquivo
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao salvar arquivo'], 500);
        }

        // Salvar no banco
        try {
            $documentId = $this->walletModel->create([
                'veiculo_id' => $vehicleId,
                'tipo_documento' => $tipoDocumento,
                'arquivo_nome' => $file['name'],
                'arquivo_path' => '/uploads/documentos/' . $fileName,
                'arquivo_tipo' => $file['type'],
                'arquivo_tamanho' => $file['size'],
                'data_vencimento' => $dataVencimento ?: null,
                'observacoes' => $observacoes,
                'data_upload' => date('Y-m-d H:i:s'),
            ]);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Documento enviado com sucesso',
                'document_id' => $documentId,
            ]);

        } catch (\Exception $e) {
            // Remover arquivo em caso de erro
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            error_log("Erro ao salvar documento: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao salvar documento'], 500);
        }
    }

    /**
     * Download de documento
     */
    public function download($id)
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar documento
        $document = $this->walletModel->findById($id);
        if (!$document) {
            http_response_code(404);
            echo "Documento não encontrado";
            return;
        }

        // Verificar se o documento pertence ao usuário
        $vehicle = $this->vehicleModel->findById($document['veiculo_id']);
        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            http_response_code(403);
            echo "Acesso negado";
            return;
        }

        // Caminho do arquivo
        $filePath = APP_PATH . '/public' . $document['arquivo_path'];

        if (!file_exists($filePath)) {
            http_response_code(404);
            echo "Arquivo não encontrado";
            return;
        }

        // Enviar arquivo
        header('Content-Type: ' . $document['arquivo_tipo']);
        header('Content-Disposition: attachment; filename="' . $document['arquivo_nome'] . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    /**
     * Visualizar documento
     */
    public function view($id)
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar documento
        $document = $this->walletModel->findById($id);
        if (!$document) {
            http_response_code(404);
            echo "Documento não encontrado";
            return;
        }

        // Verificar se o documento pertence ao usuário
        $vehicle = $this->vehicleModel->findById($document['veiculo_id']);
        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            http_response_code(403);
            echo "Acesso negado";
            return;
        }

        // Caminho do arquivo
        $filePath = APP_PATH . '/public' . $document['arquivo_path'];

        if (!file_exists($filePath)) {
            http_response_code(404);
            echo "Arquivo não encontrado";
            return;
        }

        // Enviar arquivo para visualização
        header('Content-Type: ' . $document['arquivo_tipo']);
        header('Content-Disposition: inline; filename="' . $document['arquivo_nome'] . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    /**
     * Excluir documento
     */
    public function destroy($id)
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        // Validar CSRF
        if (!$this->validateCsrf()) {
            $this->json(['sucesso' => false, 'mensagem' => 'Token CSRF inválido'], 403);
        }

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar documento
        $document = $this->walletModel->findById($id);
        if (!$document) {
            $this->json(['sucesso' => false, 'mensagem' => 'Documento não encontrado'], 404);
        }

        // Verificar se o documento pertence ao usuário
        $vehicle = $this->vehicleModel->findById($document['veiculo_id']);
        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            $this->json(['sucesso' => false, 'mensagem' => 'Acesso negado'], 403);
        }

        // Excluir arquivo físico
        $filePath = APP_PATH . '/public' . $document['arquivo_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Excluir do banco
        try {
            $this->walletModel->delete($id);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Documento excluído com sucesso',
            ]);

        } catch (\Exception $e) {
            error_log("Erro ao excluir documento: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao excluir documento'], 500);
        }
    }

    /**
     * Obter alertas de vencimento
     * 
     * @param int $userId ID do usuário
     * @return array Alertas
     */
    private function getExpiryAlerts($userId)
    {
        $alerts = [];

        // Buscar veículos do usuário
        $vehicles = $this->vehicleModel->findByUserId($userId);

        foreach ($vehicles as $vehicle) {
            // Buscar documentos do veículo
            $documents = $this->walletModel->findByVehicleId($vehicle['id']);

            foreach ($documents as $doc) {
                if ($doc['data_vencimento']) {
                    $daysUntilExpiry = $this->getDaysUntilExpiry($doc['data_vencimento']);

                    if ($daysUntilExpiry < 0) {
                        $alerts[] = [
                            'type' => 'danger',
                            'icon' => '⚠️',
                            'title' => 'Documento Vencido',
                            'message' => "{$doc['tipo_documento']} do {$vehicle['modelo']} venceu há " . abs($daysUntilExpiry) . " dias",
                            'document_id' => $doc['id'],
                            'vehicle_id' => $vehicle['id'],
                        ];
                    } elseif ($daysUntilExpiry <= 30) {
                        $alerts[] = [
                            'type' => 'warning',
                            'icon' => '⏰',
                            'title' => 'Documento Próximo do Vencimento',
                            'message' => "{$doc['tipo_documento']} do {$vehicle['modelo']} vence em {$daysUntilExpiry} dias",
                            'document_id' => $doc['id'],
                            'vehicle_id' => $vehicle['id'],
                        ];
                    }
                }
            }
        }

        return $alerts;
    }

    /**
     * Calcular dias até vencimento
     * 
     * @param string $date Data de vencimento
     * @return int Dias até vencimento (negativo se vencido)
     */
    private function getDaysUntilExpiry($date)
    {
        $today = new \DateTime();
        $expiry = new \DateTime($date);
        $diff = $today->diff($expiry);

        return $diff->invert ? -$diff->days : $diff->days;
    }
}
