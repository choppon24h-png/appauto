<?php

/**
 * ============================================================================
 * APP AUTO - Controller: ClientAuthenticationController
 * ============================================================================
 * Gerencia autenticação de fornecedores para acesso aos dados do cliente
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\ProviderAuthentication;
use App\Models\Provider;
use App\Models\Vehicle;

class ClientAuthenticationController extends Controller
{
    protected $authModel;
    protected $providerModel;
    protected $vehicleModel;

    public function __construct()
    {
        $this->authModel = new ProviderAuthentication();
        $this->providerModel = new Provider();
        $this->vehicleModel = new Vehicle();
    }

    /**
     * Listar autenticações de fornecedores
     */
    public function index()
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar autenticações do cliente
        $authentications = $this->authModel->findByClientId($userId);

        // Buscar veículos do cliente
        $vehicles = $this->vehicleModel->findByUserId($userId);

        // Agrupar por status
        $pending = [];
        $approved = [];
        $denied = [];
        $expired = [];

        foreach ($authentications as $auth) {
            // Verificar expiração
            if ($auth['status'] === 'aprovado' && $auth['expira_em']) {
                $expiresAt = strtotime($auth['expira_em']);
                if ($expiresAt < time()) {
                    $auth['status'] = 'expirado';
                    $expired[] = $auth;
                    continue;
                }
            }

            switch ($auth['status']) {
                case 'pendente':
                    $pending[] = $auth;
                    break;
                case 'aprovado':
                    $approved[] = $auth;
                    break;
                case 'negado':
                    $denied[] = $auth;
                    break;
            }
        }

        $this->view('cliente/autenticacao/index', [
            'user' => $user,
            'vehicles' => $vehicles,
            'pending' => $pending,
            'approved' => $approved,
            'denied' => $denied,
            'expired' => $expired,
            'totalAuthentications' => count($authentications),
        ]);
    }

    /**
     * Formulário para gerar token
     */
    public function create()
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar veículos do cliente
        $vehicles = $this->vehicleModel->findByUserId($userId);

        $this->view('cliente/autenticacao/create', [
            'user' => $user,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Gerar token de acesso
     */
    public function generate()
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
        $segmento = $this->sanitize($_POST['segmento'] ?? '');
        $observacoes = $this->sanitize($_POST['observacoes'] ?? '');

        // Validações
        $erros = [];

        if (!$vehicleId) {
            $erros['veiculo_id'] = 'Selecione um veículo';
        }

        if (!$segmento) {
            $erros['segmento'] = 'Selecione o segmento do fornecedor';
        }

        if (!empty($erros)) {
            $this->json(['sucesso' => false, 'erros' => $erros], 422);
        }

        // Verificar se o veículo pertence ao usuário
        $vehicle = $this->vehicleModel->findById($vehicleId);
        if (!$vehicle || $vehicle['usuario_id'] != $userId) {
            $this->json(['sucesso' => false, 'mensagem' => 'Veículo não encontrado'], 404);
        }

        // Gerar token de 6 dígitos
        $token = $this->generateToken();

        // Definir expiração (24 horas)
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Criar autenticação
        try {
            $authId = $this->authModel->create([
                'cliente_id' => $userId,
                'veiculo_id' => $vehicleId,
                'token' => $token,
                'segmento' => $segmento,
                'status' => 'pendente',
                'expira_em' => $expiresAt,
                'observacoes' => $observacoes,
                'data_geracao' => date('Y-m-d H:i:s'),
            ]);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Token gerado com sucesso',
                'token' => $token,
                'expira_em' => date('d/m/Y H:i', strtotime($expiresAt)),
                'auth_id' => $authId,
            ]);

        } catch (\Exception $e) {
            error_log("Erro ao gerar token: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao gerar token'], 500);
        }
    }

    /**
     * Aprovar solicitação de acesso
     */
    public function approve($id)
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

        // Buscar autenticação
        $auth = $this->authModel->findById($id);
        if (!$auth) {
            $this->json(['sucesso' => false, 'mensagem' => 'Autenticação não encontrada'], 404);
        }

        // Verificar se pertence ao cliente
        if ($auth['cliente_id'] != $userId) {
            $this->json(['sucesso' => false, 'mensagem' => 'Acesso negado'], 403);
        }

        // Verificar se já foi processada
        if ($auth['status'] !== 'pendente') {
            $this->json(['sucesso' => false, 'mensagem' => 'Esta solicitação já foi processada'], 400);
        }

        // Aprovar
        try {
            $this->authModel->update($id, [
                'status' => 'aprovado',
                'data_aprovacao' => date('Y-m-d H:i:s'),
            ]);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Acesso aprovado com sucesso',
            ]);

        } catch (\Exception $e) {
            error_log("Erro ao aprovar acesso: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao aprovar acesso'], 500);
        }
    }

    /**
     * Negar solicitação de acesso
     */
    public function deny($id)
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

        // Buscar autenticação
        $auth = $this->authModel->findById($id);
        if (!$auth) {
            $this->json(['sucesso' => false, 'mensagem' => 'Autenticação não encontrada'], 404);
        }

        // Verificar se pertence ao cliente
        if ($auth['cliente_id'] != $userId) {
            $this->json(['sucesso' => false, 'mensagem' => 'Acesso negado'], 403);
        }

        // Verificar se já foi processada
        if ($auth['status'] !== 'pendente') {
            $this->json(['sucesso' => false, 'mensagem' => 'Esta solicitação já foi processada'], 400);
        }

        // Negar
        try {
            $this->authModel->update($id, [
                'status' => 'negado',
                'data_aprovacao' => date('Y-m-d H:i:s'),
            ]);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Acesso negado',
            ]);

        } catch (\Exception $e) {
            error_log("Erro ao negar acesso: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao processar requisição'], 500);
        }
    }

    /**
     * Revogar acesso
     */
    public function revoke($id)
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            return;
        }

        // Validar CSRF
        if (!$this->validateCsrf()) {
            $this->json(['sucesso' => false, 'mensagem' => 'Token CSRF inválido'], 403);
        }

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar autenticação
        $auth = $this->authModel->findById($id);
        if (!$auth) {
            $this->json(['sucesso' => false, 'mensagem' => 'Autenticação não encontrada'], 404);
        }

        // Verificar se pertence ao cliente
        if ($auth['cliente_id'] != $userId) {
            $this->json(['sucesso' => false, 'mensagem' => 'Acesso negado'], 403);
        }

        // Revogar
        try {
            $this->authModel->update($id, [
                'status' => 'revogado',
                'data_revogacao' => date('Y-m-d H:i:s'),
            ]);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Acesso revogado com sucesso',
            ]);

        } catch (\Exception $e) {
            error_log("Erro ao revogar acesso: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao revogar acesso'], 500);
        }
    }

    /**
     * Excluir autenticação
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

        // Buscar autenticação
        $auth = $this->authModel->findById($id);
        if (!$auth) {
            $this->json(['sucesso' => false, 'mensagem' => 'Autenticação não encontrada'], 404);
        }

        // Verificar se pertence ao cliente
        if ($auth['cliente_id'] != $userId) {
            $this->json(['sucesso' => false, 'mensagem' => 'Acesso negado'], 403);
        }

        // Excluir
        try {
            $this->authModel->delete($id);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Autenticação excluída com sucesso',
            ]);

        } catch (\Exception $e) {
            error_log("Erro ao excluir autenticação: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao excluir autenticação'], 500);
        }
    }

    /**
     * Gerar token de 6 dígitos
     * 
     * @return string Token
     */
    private function generateToken()
    {
        // Gerar token único de 6 dígitos
        do {
            $token = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Verificar se já existe
            $existing = $this->authModel->findByToken($token);
        } while ($existing);

        return $token;
    }
}
