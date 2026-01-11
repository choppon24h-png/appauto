<?php

/**
 * ============================================================================
 * APP AUTO - Controller: ClientDashboardController
 * ============================================================================
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Vehicle;
use App\Models\Maintenance;
use App\Models\Wallet;

class ClientDashboardController extends Controller
{
    protected $vehicleModel;
    protected $maintenanceModel;
    protected $walletModel;

    public function __construct()
    {
        $this->vehicleModel = new Vehicle();
        $this->maintenanceModel = new Maintenance();
        $this->walletModel = new Wallet();
    }

    /**
     * Mostrar dashboard do cliente
     */
    public function index()
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Buscar estatísticas
        $stats = $this->getStats($userId);

        // Buscar últimos veículos
        $latestVehicles = $this->vehicleModel->findByUserId($userId, 5);

        // Buscar últimas manutenções
        $latestMaintenances = $this->maintenanceModel->findByUserId($userId, 5);

        // Buscar alertas
        $alerts = $this->getAlerts($userId);

        $this->view('cliente/dashboard', [
            'user' => $user,
            'stats' => $stats,
            'latestVehicles' => $latestVehicles,
            'latestMaintenances' => $latestMaintenances,
            'alerts' => $alerts,
        ]);
    }

    /**
     * Obter estatísticas do cliente
     * 
     * @param int $userId ID do usuário
     * @return array Estatísticas
     */
    private function getStats($userId)
    {
        // Total de veículos
        $totalVehicles = $this->vehicleModel->countByUserId($userId);

        // Total de manutenções
        $totalMaintenances = $this->maintenanceModel->countByUserId($userId);

        // Total de documentos
        $totalDocuments = $this->walletModel->countByUserId($userId);

        // Média de KM/L (simulado - será implementado com abastecimentos)
        $avgKmL = 12.5;

        // Gasto total (simulado - será implementado com abastecimentos)
        $totalSpent = 0;

        return [
            'total_vehicles' => $totalVehicles,
            'total_maintenances' => $totalMaintenances,
            'total_documents' => $totalDocuments,
            'avg_km_l' => $avgKmL,
            'total_spent' => $totalSpent,
        ];
    }

    /**
     * Obter alertas do cliente
     * 
     * @param int $userId ID do usuário
     * @return array Alertas
     */
    private function getAlerts($userId)
    {
        $alerts = [];

        // Buscar veículos do usuário
        $vehicles = $this->vehicleModel->findByUserId($userId);

        foreach ($vehicles as $vehicle) {
            // Verificar documentos vencidos ou próximos do vencimento
            $documents = $this->walletModel->findByVehicleId($vehicle['id']);

            foreach ($documents as $doc) {
                if ($doc['data_vencimento']) {
                    $daysUntilExpiry = $this->getDaysUntilExpiry($doc['data_vencimento']);

                    if ($daysUntilExpiry < 0) {
                        $alerts[] = [
                            'type' => 'danger',
                            'icon' => '⚠️',
                            'title' => 'Documento Vencido',
                            'message' => "{$doc['tipo_documento']} do veículo {$vehicle['modelo']} venceu há " . abs($daysUntilExpiry) . " dias",
                            'vehicle_id' => $vehicle['id'],
                            'document_id' => $doc['id'],
                        ];
                    } elseif ($daysUntilExpiry <= 30) {
                        $alerts[] = [
                            'type' => 'warning',
                            'icon' => '⏰',
                            'title' => 'Documento Próximo do Vencimento',
                            'message' => "{$doc['tipo_documento']} do veículo {$vehicle['modelo']} vence em {$daysUntilExpiry} dias",
                            'vehicle_id' => $vehicle['id'],
                            'document_id' => $doc['id'],
                        ];
                    }
                }
            }

            // Verificar manutenções pendentes
            $maintenances = $this->maintenanceModel->findByVehicleId($vehicle['id']);

            foreach ($maintenances as $maintenance) {
                if ($maintenance['status'] === 'pendente') {
                    $alerts[] = [
                        'type' => 'info',
                        'icon' => '🔧',
                        'title' => 'Manutenção Pendente',
                        'message' => "{$maintenance['tipo_manutencao']} do veículo {$vehicle['modelo']} está pendente",
                        'vehicle_id' => $vehicle['id'],
                        'maintenance_id' => $maintenance['id'],
                    ];
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

    /**
     * API: Obter dados para gráficos
     */
    public function chartData()
    {
        $this->requireAuth();
        $this->requireRole('cliente');

        $user = $this->getAuthUser();
        $userId = $user['id'];

        // Dados simulados - será implementado com abastecimentos reais
        $data = [
            'consumption' => [
                'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                'datasets' => [
                    [
                        'label' => 'Consumo (KM/L)',
                        'data' => [12.5, 13.2, 11.8, 12.9, 13.5, 12.7],
                        'backgroundColor' => 'rgba(102, 126, 234, 0.2)',
                        'borderColor' => 'rgba(102, 126, 234, 1)',
                        'borderWidth' => 2,
                    ]
                ]
            ],
            'expenses' => [
                'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                'datasets' => [
                    [
                        'label' => 'Gastos (R$)',
                        'data' => [450, 520, 380, 490, 510, 470],
                        'backgroundColor' => 'rgba(118, 75, 162, 0.2)',
                        'borderColor' => 'rgba(118, 75, 162, 1)',
                        'borderWidth' => 2,
                    ]
                ]
            ],
        ];

        $this->json(['sucesso' => true, 'data' => $data]);
    }
}
