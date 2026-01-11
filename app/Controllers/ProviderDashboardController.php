<?php

/**
 * ============================================================================
 * APP AUTO - Controller: ProviderDashboardController
 * ============================================================================
 * Dashboard do fornecedor com estatísticas e informações
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\ServiceOrder;
use App\Models\Provider;

class ProviderDashboardController extends Controller
{
    protected $serviceOrderModel;
    protected $providerModel;

    public function __construct()
    {
        $this->serviceOrderModel = new ServiceOrder();
        $this->providerModel = new Provider();
    }

    /**
     * Dashboard do fornecedor
     */
    public function index()
    {
        $this->requireAuth();
        $this->requireRole('fornecedor');

        $user = $this->getAuthUser();
        $providerId = $user['fornecedor_id'] ?? null;

        if (!$providerId) {
            $_SESSION['error'] = 'Fornecedor não encontrado';
            header('Location: /logout');
            exit;
        }

        // Buscar informações do fornecedor
        $provider = $this->providerModel->findById($providerId);

        // Buscar O.S do fornecedor
        $allOrders = $this->serviceOrderModel->findByProviderId($providerId);

        // Filtrar por status
        $pending = array_filter($allOrders, fn($os) => $os['status'] === 'pendente');
        $inProgress = array_filter($allOrders, fn($os) => $os['status'] === 'em_andamento');
        $completed = array_filter($allOrders, fn($os) => $os['status'] === 'concluida');

        // Calcular estatísticas
        $stats = $this->calculateStats($allOrders, $completed);

        // Últimas O.S
        $recentOrders = array_slice($allOrders, 0, 5);

        // Dados para gráficos
        $chartData = $this->getChartData($allOrders);

        $this->view('fornecedor/dashboard', [
            'user' => $user,
            'provider' => $provider,
            'stats' => $stats,
            'pending' => array_values($pending),
            'inProgress' => array_values($inProgress),
            'completed' => array_values($completed),
            'recentOrders' => $recentOrders,
            'chartData' => $chartData,
        ]);
    }

    /**
     * API para dados dos gráficos
     */
    public function chartData()
    {
        $this->requireAuth();
        $this->requireRole('fornecedor');

        $user = $this->getAuthUser();
        $providerId = $user['fornecedor_id'] ?? null;

        if (!$providerId) {
            $this->json(['error' => 'Fornecedor não encontrado'], 404);
        }

        // Buscar O.S do fornecedor
        $allOrders = $this->serviceOrderModel->findByProviderId($providerId);

        // Dados para gráficos
        $chartData = $this->getChartData($allOrders);

        $this->json($chartData);
    }

    /**
     * Calcular estatísticas
     */
    private function calculateStats($allOrders, $completed)
    {
        // Total de O.S
        $totalOrders = count($allOrders);

        // Receita total
        $totalRevenue = array_sum(array_column($completed, 'valor_total'));

        // Clientes únicos
        $uniqueClients = count(array_unique(array_column($allOrders, 'cliente_id')));

        // Avaliação média
        $ratings = array_filter(array_column($completed, 'avaliacao'));
        $avgRating = !empty($ratings) ? array_sum($ratings) / count($ratings) : 0;

        // O.S pendentes
        $pendingCount = count(array_filter($allOrders, fn($os) => $os['status'] === 'pendente'));

        // O.S em andamento
        $inProgressCount = count(array_filter($allOrders, fn($os) => $os['status'] === 'em_andamento'));

        // O.S concluídas
        $completedCount = count($completed);

        // Receita do mês atual
        $currentMonth = date('Y-m');
        $monthlyRevenue = array_sum(array_map(function($os) use ($currentMonth) {
            if (strpos($os['data_conclusao'] ?? '', $currentMonth) === 0) {
                return $os['valor_total'];
            }
            return 0;
        }, $completed));

        return [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'unique_clients' => $uniqueClients,
            'avg_rating' => round($avgRating, 1),
            'pending_count' => $pendingCount,
            'in_progress_count' => $inProgressCount,
            'completed_count' => $completedCount,
            'monthly_revenue' => $monthlyRevenue,
        ];
    }

    /**
     * Obter dados para gráficos
     */
    private function getChartData($allOrders)
    {
        // Receita dos últimos 6 meses
        $revenueByMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            
            $revenue = array_sum(array_map(function($os) use ($month) {
                if ($os['status'] === 'concluida' && strpos($os['data_conclusao'] ?? '', $month) === 0) {
                    return $os['valor_total'];
                }
                return 0;
            }, $allOrders));

            $revenueByMonth[] = [
                'month' => $monthName,
                'revenue' => $revenue,
            ];
        }

        // O.S por status
        $ordersByStatus = [
            'pendente' => count(array_filter($allOrders, fn($os) => $os['status'] === 'pendente')),
            'em_andamento' => count(array_filter($allOrders, fn($os) => $os['status'] === 'em_andamento')),
            'concluida' => count(array_filter($allOrders, fn($os) => $os['status'] === 'concluida')),
            'cancelada' => count(array_filter($allOrders, fn($os) => $os['status'] === 'cancelada')),
        ];

        return [
            'revenue_by_month' => $revenueByMonth,
            'orders_by_status' => $ordersByStatus,
        ];
    }
}
