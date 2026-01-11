<?php

/**
 * ============================================================================
 * APP AUTO - Controller: ClientServiceOrderController
 * ============================================================================
 * Gerencia visualização de Ordens de Serviço do cliente
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\ServiceOrder;
use App\Models\Vehicle;

class ClientServiceOrderController extends Controller
{
    protected $serviceOrderModel;
    protected $vehicleModel;

    public function __construct()
    {
        $this->serviceOrderModel = new ServiceOrder();
        $this->vehicleModel = new Vehicle();
    }

    /**
     * Listar ordens de serviço do cliente
     */
    public function index()
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar O.S do cliente
        $serviceOrders = $this->serviceOrderModel->findByClientId($userId);

        // Buscar veículos do cliente
        $vehicles = $this->vehicleModel->findByUserId($userId);

        // Agrupar por status
        $pending = [];
        $inProgress = [];
        $completed = [];
        $cancelled = [];

        foreach ($serviceOrders as $os) {
            switch ($os['status']) {
                case 'pendente':
                    $pending[] = $os;
                    break;
                case 'em_andamento':
                    $inProgress[] = $os;
                    break;
                case 'concluida':
                    $completed[] = $os;
                    break;
                case 'cancelada':
                    $cancelled[] = $os;
                    break;
            }
        }

        // Calcular estatísticas
        $stats = [
            'total' => count($serviceOrders),
            'pending' => count($pending),
            'in_progress' => count($inProgress),
            'completed' => count($completed),
            'total_value' => array_sum(array_column($completed, 'valor_total')),
        ];

        $this->view('cliente/os/index', [
            'user' => $user,
            'vehicles' => $vehicles,
            'pending' => $pending,
            'inProgress' => $inProgress,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'stats' => $stats,
        ]);
    }

    /**
     * Exibir detalhes da O.S
     */
    public function show($id)
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar O.S
        $os = $this->serviceOrderModel->findById($id);
        if (!$os) {
            $_SESSION['error'] = 'Ordem de serviço não encontrada';
            header('Location: /cliente/os');
            exit;
        }

        // Verificar se pertence ao cliente
        if ($os['cliente_id'] != $userId) {
            $_SESSION['error'] = 'Acesso negado';
            header('Location: /cliente/os');
            exit;
        }

        // Buscar veículo
        $vehicle = $this->vehicleModel->findById($os['veiculo_id']);

        $this->view('cliente/os/show', [
            'user' => $user,
            'os' => $os,
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Avaliar serviço
     */
    public function rate($id)
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

        // Buscar O.S
        $os = $this->serviceOrderModel->findById($id);
        if (!$os) {
            $this->json(['sucesso' => false, 'mensagem' => 'Ordem de serviço não encontrada'], 404);
        }

        // Verificar se pertence ao cliente
        if ($os['cliente_id'] != $userId) {
            $this->json(['sucesso' => false, 'mensagem' => 'Acesso negado'], 403);
        }

        // Verificar se está concluída
        if ($os['status'] !== 'concluida') {
            $this->json(['sucesso' => false, 'mensagem' => 'Apenas O.S concluídas podem ser avaliadas'], 400);
        }

        // Validar entrada
        $rating = intval($_POST['rating'] ?? 0);
        $comment = $this->sanitize($_POST['comment'] ?? '');

        // Validações
        if ($rating < 1 || $rating > 5) {
            $this->json(['sucesso' => false, 'mensagem' => 'Avaliação deve ser entre 1 e 5'], 422);
        }

        // Atualizar O.S
        try {
            $this->serviceOrderModel->update($id, [
                'avaliacao' => $rating,
                'comentario_avaliacao' => $comment,
                'data_avaliacao' => date('Y-m-d H:i:s'),
            ]);

            $this->json([
                'sucesso' => true,
                'mensagem' => 'Avaliação registrada com sucesso',
            ]);

        } catch (\Exception $e) {
            error_log("Erro ao avaliar serviço: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao registrar avaliação'], 500);
        }
    }
}
