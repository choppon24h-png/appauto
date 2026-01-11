<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Fornecedor - APP AUTO</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
    <?php include APP_PATH . '/app/Views/fornecedor/_sidebar.php'; ?>
    <main class="main-content" id="main-content">
        <header class="content-header">
            <div class="header-left">
                <button class="mobile-menu-btn" id="mobile-menu-btn"><span></span><span></span><span></span></button>
                <h1 class="page-title">Dashboard</h1>
            </div>
            <div class="header-right">
                <span class="header-greeting">Bem-vindo, <?= htmlspecialchars($provider['nome_fantasia'] ?? $user['nome']) ?>!</span>
            </div>
        </header>
        <div id="message-container"></div>
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📋</div>
                    <div class="stat-value"><?= $stats['total_orders'] ?></div>
                    <div class="stat-label">Total de O.S</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-value">R$ <?= number_format($stats['total_revenue'], 2, ',', '.') ?></div>
                    <div class="stat-label">Receita Total</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-value"><?= $stats['unique_clients'] ?></div>
                    <div class="stat-label">Clientes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⭐</div>
                    <div class="stat-value"><?= $stats['avg_rating'] > 0 ? number_format($stats['avg_rating'], 1) : '-' ?></div>
                    <div class="stat-label">Avaliação Média</div>
                </div>
            </div>
        </section>
        <section class="charts-section">
            <div class="charts-grid">
                <div class="chart-card">
                    <h3 class="chart-title">Receita dos Últimos 6 Meses</h3>
                    <canvas id="revenue-chart"></canvas>
                </div>
                <div class="chart-card">
                    <h3 class="chart-title">O.S por Status</h3>
                    <canvas id="status-chart"></canvas>
                </div>
            </div>
        </section>
        <section class="recent-section">
            <h2 class="section-title">Últimas Ordens de Serviço</h2>
            <?php if (!empty($recentOrders)): ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Veículo</th>
                            <th>Status</th>
                            <th>Valor</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $os): ?>
                        <tr>
                            <td>#<?= str_pad($os['id'], 6, '0', STR_PAD_LEFT) ?></td>
                            <td><?= htmlspecialchars($os['cliente_nome'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($os['veiculo_placa'] ?? 'N/A') ?></td>
                            <td><span class="badge badge-<?= $os['status'] === 'concluida' ? 'success' : ($os['status'] === 'em_andamento' ? 'info' : 'warning') ?>"><?= ucfirst(str_replace('_', ' ', $os['status'])) ?></span></td>
                            <td>R$ <?= number_format($os['valor_total'], 2, ',', '.') ?></td>
                            <td><?= date('d/m/Y', strtotime($os['data_abertura'])) ?></td>
                            <td><a href="<?= BASE_URL ?>/fornecedor/os/<?= $os['id'] ?>" class="btn btn-sm btn-primary">Ver</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h3 class="empty-title">Nenhuma O.S registrada</h3>
                <p class="empty-message">Comece criando sua primeira ordem de serviço.</p>
                <a href="<?= BASE_URL ?>/fornecedor/os/criar" class="btn btn-primary">Criar O.S</a>
            </div>
            <?php endif; ?>
        </section>
    </main>
    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
    <script>
    const chartData = <?= json_encode($chartData) ?>;
    const revenueCtx = document.getElementById('revenue-chart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: chartData.revenue_by_month.map(d => d.month),
            datasets: [{
                label: 'Receita (R$)',
                data: chartData.revenue_by_month.map(d => d.revenue),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {display: false}
            }
        }
    });
    const statusCtx = document.getElementById('status-chart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pendente', 'Em Andamento', 'Concluída', 'Cancelada'],
            datasets: [{
                data: [
                    chartData.orders_by_status.pendente,
                    chartData.orders_by_status.em_andamento,
                    chartData.orders_by_status.concluida,
                    chartData.orders_by_status.cancelada
                ],
                backgroundColor: ['#f6ad55', '#4299e1', '#48bb78', '#f56565']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
    </script>
</body>
</html>
