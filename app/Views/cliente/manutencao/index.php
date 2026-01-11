<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Manutenção - APP AUTO</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/maintenance.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include APP_PATH . '/app/Views/cliente/_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <!-- Header -->
        <header class="content-header">
            <div class="header-left">
                <button class="mobile-menu-btn" id="mobile-menu-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <h1 class="page-title">Manutenção</h1>
            </div>
            <div class="header-right">
                <a href="<?= BASE_URL ?>/cliente/manutencao/novo" class="btn btn-primary">
                    Registrar Manutenção
                </a>
            </div>
        </header>

        <!-- Mensagens -->
        <div id="message-container"></div>

        <!-- Estatísticas -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">🔧</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= $totalMaintenances ?></div>
                        <div class="stat-label">Total de Manutenções</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-info">
                        <div class="stat-value">R$ <?= number_format($totalCost, 2, ',', '.') ?></div>
                        <div class="stat-label">Custo Total</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⚠️</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= count($alerts) ?></div>
                        <div class="stat-label">Alertas Ativos</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🚗</div>
                    <div class="stat-info">
                        <div class="stat-value"><?= count($vehicles) ?></div>
                        <div class="stat-label">Veículos</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Alertas de Manutenção -->
        <?php if (!empty($alerts)): ?>
        <section class="alerts-section">
            <div class="section-header">
                <h2 class="section-title">Alertas de Manutenção</h2>
            </div>
            <div class="alerts-list">
                <?php foreach ($alerts as $alert): ?>
                <div class="alert alert-<?= $alert['type'] ?>">
                    <div class="alert-icon"><?= $alert['icon'] ?></div>
                    <div class="alert-content">
                        <div class="alert-title"><?= htmlspecialchars($alert['title']) ?></div>
                        <div class="alert-message"><?= htmlspecialchars($alert['message']) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Manutenções por Veículo -->
        <?php if (!empty($maintenancesByVehicle)): ?>
        <?php foreach ($maintenancesByVehicle as $data): ?>
        <section class="vehicle-maintenances-section">
            <div class="section-header">
                <h2 class="section-title">
                    <?= htmlspecialchars($data['vehicle']['marca'] . ' ' . $data['vehicle']['modelo']) ?>
                    <span class="vehicle-placa"><?= htmlspecialchars($data['vehicle']['placa']) ?></span>
                </h2>
            </div>

            <div class="maintenances-list">
                <?php foreach ($data['maintenances'] as $m): ?>
                <div class="maintenance-card" data-maintenance-id="<?= $m['id'] ?>">
                    <div class="maintenance-header">
                        <div class="maintenance-type">
                            <span class="type-icon"><?= getMaintenanceIcon($m['tipo_manutencao']) ?></span>
                            <span class="type-name"><?= htmlspecialchars($m['tipo_manutencao']) ?></span>
                        </div>
                        <span class="badge badge-<?= $m['status'] === 'concluida' ? 'success' : 'warning' ?>">
                            <?= $m['status'] === 'concluida' ? 'Concluída' : 'Pendente' ?>
                        </span>
                    </div>

                    <div class="maintenance-body">
                        <div class="maintenance-description">
                            <?= htmlspecialchars($m['descricao']) ?>
                        </div>

                        <div class="maintenance-info-grid">
                            <div class="info-item">
                                <span class="info-label">Data</span>
                                <span class="info-value"><?= date('d/m/Y', strtotime($m['data_manutencao'])) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">KM</span>
                                <span class="info-value"><?= number_format($m['km_atual'], 0, ',', '.') ?> km</span>
                            </div>
                            <?php if ($m['proxima_manutencao_km']): ?>
                            <div class="info-item">
                                <span class="info-label">Próxima</span>
                                <span class="info-value"><?= number_format($m['proxima_manutencao_km'], 0, ',', '.') ?> km</span>
                            </div>
                            <?php endif; ?>
                            <div class="info-item">
                                <span class="info-label">Custo</span>
                                <span class="info-value">R$ <?= number_format($m['custo'], 2, ',', '.') ?></span>
                            </div>
                            <?php if ($m['fornecedor']): ?>
                            <div class="info-item">
                                <span class="info-label">Fornecedor</span>
                                <span class="info-value"><?= htmlspecialchars($m['fornecedor']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="maintenance-actions">
                        <a href="<?= BASE_URL ?>/cliente/manutencao/<?= $m['id'] ?>/editar" class="btn-action" title="Editar">
                            ✏️
                        </a>
                        <button class="btn-action btn-danger" onclick="deleteMaintenance(<?= $m['id'] ?>, '<?= htmlspecialchars($m['tipo_manutencao']) ?>')" title="Excluir">
                            🗑️
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endforeach; ?>
        <?php else: ?>
        <!-- Empty State -->
        <section class="empty-state">
            <div class="empty-icon">🔧</div>
            <h3 class="empty-title">Nenhuma manutenção registrada</h3>
            <p class="empty-message">Comece registrando as manutenções dos seus veículos para manter o histórico e receber alertas.</p>
            <a href="<?= BASE_URL ?>/cliente/manutencao/novo" class="btn btn-primary">Registrar Primeira Manutenção</a>
        </section>
        <?php endif; ?>
    </main>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal" id="delete-modal" style="display: none;">
        <div class="modal-overlay" onclick="closeDeleteModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirmar Exclusão</h3>
                <button class="modal-close" onclick="closeDeleteModal()">×</button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir a manutenção <strong id="maintenance-name"></strong>?</p>
                <p class="text-danger">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancelar</button>
                <button class="btn btn-danger" id="confirm-delete-btn">Excluir</button>
            </div>
        </div>
    </div>

    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/maintenance.js"></script>
</body>
</html>

<?php
// Função auxiliar para ícones de manutenção
function getMaintenanceIcon($tipo) {
    $icons = [
        'Troca de Óleo' => '🛢️',
        'Troca de Filtros' => '🔧',
        'Troca de Pneus' => '🛞',
        'Revisão Geral' => '🔍',
        'Alinhamento e Balanceamento' => '⚙️',
        'Freios' => '🛑',
        'Suspensão' => '🔩',
        'Elétrica' => '⚡',
        'Ar Condicionado' => '❄️',
        'Outro' => '🔧',
    ];
    return $icons[$tipo] ?? '🔧';
}
?>
