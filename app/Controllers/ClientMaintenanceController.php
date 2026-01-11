<?php

/**
 * ============================================================================
 * APP AUTO - Controller: ClientMaintenanceController
 * ============================================================================
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Maintenance;
use App\Models\Vehicle;

class ClientMaintenanceController extends Controller
{
    protected $maintenanceModel;
    protected $vehicleModel;

    public function __construct()
    {
        $this->maintenanceModel = new Maintenance();
        $this->vehicleModel = new Vehicle();
    }

    /**
     * Listar manutenções
     */
    public function index()
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar veículos do usuário
        $vehicles = $this->vehicleModel->findByUserId($userId);

        // Buscar manutenções agrupadas por veículo
        $maintenancesByVehicle = [];
        $totalMaintenances = 0;
        $totalCost = 0;

        foreach ($vehicles as $vehicle) {
            $maintenances = $this->maintenanceModel->findByVehicleId($vehicle['id']);
            
            if (!empty($maintenances)) {
                $maintenancesByVehicle[$vehicle['id']] = [
                    'vehicle' => $vehicle,
                    'maintenances' => $maintenances,
                ];
                
                $totalMaintenances += count($maintenances);
                
                foreach ($maintenances as $m) {
                    $totalCost += $m['custo'];
                }
            }
        }

        // Buscar alertas de manutenção
        $alerts = $this->getMaintenanceAlerts($userId);

        $this->view('cliente/manutencao/index', [
            'user' => $user,
            'vehicles' => $vehicles,
            'maintenancesByVehicle' => $maintenancesByVehicle,
            'alerts' => $alerts,
            'totalMaintenances' => $totalMaintenances,
            'totalCost' => $totalCost,
        ]);
    }

    /**
     * Formulário de nova manutenção
     */
    public function create()
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar veículos do usuário
        $vehicles = $this->vehicleModel->findByUserId($userId);

        $this->view('cliente/manutencao/create', [
            'user' => $user,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Salvar nova manutenção
     */
    public function store()
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
        $tipoManutencao = $this->sanitize($_POST['tipo_manutencao'] ?? '');
        $descricao = $this->sanitize($_POST['descricao'] ?? '');
        $kmAtual = $this->sanitize($_POST['km_atual'] ?? '');
        $proximaManutencaoKm = $this->sanitize($_POST['proxima_manutencao_km'] ?? '');
        $custo = $this->sanitize($_POST['custo'] ?? '0');
        $fornecedor = $this->sanitize($_POST['fornecedor'] ?? '');
        $dataManutencao = $this->sanitize($_POST['data_manutencao'] ?? date('Y-m-d'));
        $status = $this->sanitize($_POST['status'] ?? 'concluida');

        // Validações
        $erros = [];

        if (!$vehicleId) {
            $erros['veiculo_id'] = 'Selecione um veículo';
        }

        if (!$tipoManutencao) {
            $erros['tipo_manutencao'] = 'Selecione o tipo de manutenção';
        }

        if (!$descricao || strlen($descricao) < 5) {
            $erros['descricao'] = 'Descrição deve ter pelo menos 5 caracteres';
        }

        if (!$kmAtual || !is_numeric($kmAtual) || $kmAtual < 0) {
            $erros['km_atual'] = 'KM atual inválido';
        }

        if ($proximaManutencaoKm && (!is_numeric($proximaManutencaoKm) || $proximaManutencaoKm <= $kmAtual)) {
            $erros['proxima_manutencao_km'] = 'Próxima manutenção deve ser maior que KM atual';
        }

        if (!empty($erros)) {
            $this->json(['sucesso' => false, 'erros' => $erros], 422);
        }

        // Verificar se o veículo pertence ao usuário
        $vehicle = $this->vehicleModel->findById($vehicleId);
        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            $this->json(['sucesso' => false, 'mensagem' => 'Veículo não encontrado'], 404);
        }

        // Criar manutenção
        try {
            // Limpar custo (remover formatação)
            $custo = str_replace(['.', ','], ['', '.'], $custo);

            $maintenanceId = $this->maintenanceModel->create([
                'veiculo_id' => $vehicleId,
                'tipo_manutencao' => $tipoManutencao,
                'descricao' => $descricao,
                'km_atual' => $kmAtual,
                'proxima_manutencao_km' => $proximaManutencaoKm ?: null,
                'custo' => $custo,
                'fornecedor' => $fornecedor,
                'data_manutencao' => $dataManutencao,
                'status' => $status,
                'data_registro' => date('Y-m-d H:i:s'),
            ]);

            // Atualizar KM do veículo
            $this->vehicleModel->update($vehicleId, [
                'km_atual' => $kmAtual,
            ]);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Manutenção registrada com sucesso',
                'maintenance_id' => $maintenanceId,
                'redirect' => '/cliente/manutencao',
            ]);

        } catch (\Exception $e) {
            error_log("Erro ao criar manutenção: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao registrar manutenção'], 500);
        }
    }

    /**
     * Exibir detalhes da manutenção
     */
    public function show($id)
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar manutenção
        $maintenance = $this->maintenanceModel->findById($id);
        if (!$maintenance) {
            http_response_code(404);
            echo "Manutenção não encontrada";
            return;
        }

        // Verificar se a manutenção pertence ao usuário
        $vehicle = $this->vehicleModel->findById($maintenance['veiculo_id']);
        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            http_response_code(403);
            echo "Acesso negado";
            return;
        }

        $this->view('cliente/manutencao/show', [
            'user' => $user,
            'maintenance' => $maintenance,
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Formulário de edição
     */
    public function edit($id)
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar manutenção
        $maintenance = $this->maintenanceModel->findById($id);
        if (!$maintenance) {
            http_response_code(404);
            echo "Manutenção não encontrada";
            return;
        }

        // Verificar se a manutenção pertence ao usuário
        $vehicle = $this->vehicleModel->findById($maintenance['veiculo_id']);
        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            http_response_code(403);
            echo "Acesso negado";
            return;
        }

        // Buscar veículos do usuário
        $vehicles = $this->vehicleModel->findByUserId($userId);

        $this->view('cliente/manutencao/edit', [
            'user' => $user,
            'maintenance' => $maintenance,
            'vehicle' => $vehicle,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Atualizar manutenção
     */
    public function update($id)
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        // Validar CSRF
        if (!$this->validateCsrf()) {
            $this->json(['sucesso' => false, 'mensagem' => 'Token CSRF inválido'], 403);
        }

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar manutenção
        $maintenance = $this->maintenanceModel->findById($id);
        if (!$maintenance) {
            $this->json(['sucesso' => false, 'mensagem' => 'Manutenção não encontrada'], 404);
        }

        // Verificar se a manutenção pertence ao usuário
        $vehicle = $this->vehicleModel->findById($maintenance['veiculo_id']);
        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            $this->json(['sucesso' => false, 'mensagem' => 'Acesso negado'], 403);
        }

        // Validar entrada
        $tipoManutencao = $this->sanitize($_POST['tipo_manutencao'] ?? '');
        $descricao = $this->sanitize($_POST['descricao'] ?? '');
        $kmAtual = $this->sanitize($_POST['km_atual'] ?? '');
        $proximaManutencaoKm = $this->sanitize($_POST['proxima_manutencao_km'] ?? '');
        $custo = $this->sanitize($_POST['custo'] ?? '0');
        $fornecedor = $this->sanitize($_POST['fornecedor'] ?? '');
        $dataManutencao = $this->sanitize($_POST['data_manutencao'] ?? '');
        $status = $this->sanitize($_POST['status'] ?? 'concluida');

        // Validações
        $erros = [];

        if (!$tipoManutencao) {
            $erros['tipo_manutencao'] = 'Selecione o tipo de manutenção';
        }

        if (!$descricao || strlen($descricao) < 5) {
            $erros['descricao'] = 'Descrição deve ter pelo menos 5 caracteres';
        }

        if (!$kmAtual || !is_numeric($kmAtual) || $kmAtual < 0) {
            $erros['km_atual'] = 'KM atual inválido';
        }

        if (!empty($erros)) {
            $this->json(['sucesso' => false, 'erros' => $erros], 422);
        }

        // Atualizar manutenção
        try {
            // Limpar custo
            $custo = str_replace(['.', ','], ['', '.'], $custo);

            $this->maintenanceModel->update($id, [
                'tipo_manutencao' => $tipoManutencao,
                'descricao' => $descricao,
                'km_atual' => $kmAtual,
                'proxima_manutencao_km' => $proximaManutencaoKm ?: null,
                'custo' => $custo,
                'fornecedor' => $fornecedor,
                'data_manutencao' => $dataManutencao,
                'status' => $status,
            ]);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Manutenção atualizada com sucesso',
                'redirect' => '/cliente/manutencao',
            ]);

        } catch (\Exception $e) {
            error_log("Erro ao atualizar manutenção: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao atualizar manutenção'], 500);
        }
    }

    /**
     * Excluir manutenção
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

        // Buscar manutenção
        $maintenance = $this->maintenanceModel->findById($id);
        if (!$maintenance) {
            $this->json(['sucesso' => false, 'mensagem' => 'Manutenção não encontrada'], 404);
        }

        // Verificar se a manutenção pertence ao usuário
        $vehicle = $this->vehicleModel->findById($maintenance['veiculo_id']);
        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            $this->json(['sucesso' => false, 'mensagem' => 'Acesso negado'], 403);
        }

        // Excluir manutenção
        try {
            $this->maintenanceModel->delete($id);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Manutenção excluída com sucesso',
            ]);

        } catch (\Exception $e) {
            error_log("Erro ao excluir manutenção: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao excluir manutenção'], 500);
        }
    }

    /**
     * Obter alertas de manutenção
     * 
     * @param int $userId ID do usuário
     * @return array Alertas
     */
    private function getMaintenanceAlerts($userId)
    {
        $alerts = [];

        // Buscar veículos do usuário
        $vehicles = $this->vehicleModel->findByUserId($userId);

        foreach ($vehicles as $vehicle) {
            // Buscar manutenções do veículo
            $maintenances = $this->maintenanceModel->findByVehicleId($vehicle['id']);

            foreach ($maintenances as $m) {
                if ($m['proxima_manutencao_km'] && $m['status'] === 'concluida') {
                    $kmRestante = $m['proxima_manutencao_km'] - $vehicle['km_atual'];

                    if ($kmRestante <= 0) {
                        $alerts[] = [
                            'type' => 'danger',
                            'icon' => '⚠️',
                            'title' => 'Manutenção Atrasada',
                            'message' => "{$m['tipo_manutencao']} do {$vehicle['modelo']} está atrasada",
                            'maintenance_id' => $m['id'],
                            'vehicle_id' => $vehicle['id'],
                        ];
                    } elseif ($kmRestante <= 1000) {
                        $alerts[] = [
                            'type' => 'warning',
                            'icon' => '⏰',
                            'title' => 'Manutenção Próxima',
                            'message' => "{$m['tipo_manutencao']} do {$vehicle['modelo']} em {$kmRestante} KM",
                            'maintenance_id' => $m['id'],
                            'vehicle_id' => $vehicle['id'],
                        ];
                    }
                }
            }
        }

        return $alerts;
    }
}
