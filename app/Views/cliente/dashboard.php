<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard - APP AUTO</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="APP AUTO" class="sidebar-logo">
            <button class="sidebar-toggle" id="sidebar-toggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <nav class="sidebar-nav">
            <a href="<?= BASE_URL ?>/cliente/dashboard" class="nav-item active">
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="<?= BASE_URL ?>/cliente/veiculos" class="nav-item">
                <span class="nav-text">Meus Veículos</span>
            </a>
            <a href="<?= BASE_URL ?>/cliente/carteira" class="nav-item">
                <span class="nav-text">Carteira Digital</span>
            </a>
            <a href="<?= BASE_URL ?>/cliente/manutencao" class="nav-item">
                <span class="nav-text">Manutenção</span>
            </a>
            <a href="<?= BASE_URL ?>/cliente/autenticacao" class="nav-item">
                <span class="nav-text">Fornecedores</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar"><?= strtoupper(substr($user['nome'], 0, 2)) ?></div>
                <div class="user-details">
                    <div class="user-name"><?= htmlspecialchars($user['nome']) ?></div>
                    <div class="user-email"><?= htmlspecialchars($user['email']) ?></div>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/perfil" class="nav-item">
                <span class="nav-text">Perfil</span>
            </a>
            <form action="<?= BASE_URL ?>/logout" method="POST">
                <button type="submit" class="nav-item logout-btn">
                    <span class="nav-text">Sair</span>
                </button>
            </form>
        </div>
    </aside>

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
                <h1 class="page-title">Dashboard</h1>
            </div>
            <div class="header-right">
                <span class="welcome-text">Olá, <?= htmlspecialchars(explode(' ', $user['nome'])[0]) ?>!</span>
            </div>
        </header>

        <!-- Stats Cards -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-primary">🚗</div>
                    <div class="stat-content">
                        <div class="stat-value"><?= $stats['total_vehicles'] ?></div>
                        <div class="stat-label">Veículos</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-success">🔧</div>
                    <div class="stat-content">
                        <div class="stat-value"><?= $stats['total_maintenances'] ?></div>
                        <div class="stat-label">Manutenções</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-warning">📄</div>
                    <div class="stat-content">
                        <div class="stat-value"><?= $stats['total_documents'] ?></div>
                        <div class="stat-label">Documentos</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-info">⛽</div>
                    <div class="stat-content">
                        <div class="stat-value"><?= number_format($stats['avg_km_l'], 1, ',', '.') ?></div>
                        <div class="stat-label">Média KM/L</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Charts Section -->
        <section class="charts-section">
            <div class="charts-grid">
                <div class="chart-card">
                    <div class="card-header">
                        <h3 class="card-title">Consumo Mensal (KM/L)</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="consumption-chart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="card-header">
                        <h3 class="card-title">Gastos Mensais (R$)</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="expenses-chart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Alerts Section -->
        <?php if (!empty($alerts)): ?>
        <section class="alerts-section">
            <div class="section-header">
                <h2 class="section-title">Alertas</h2>
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

        <!-- Latest Vehicles Section -->
        <?php if (!empty($latestVehicles)): ?>
        <section class="latest-section">
            <div class="section-header">
                <h2 class="section-title">Últimos Veículos Cadastrados</h2>
                <a href="<?= BASE_URL ?>/cliente/veiculos" class="btn btn-link">Ver todos</a>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Modelo</th>
                            <th>Placa</th>
                            <th>Ano</th>
                            <th>KM Atual</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latestVehicles as $vehicle): ?>
                        <tr>
                            <td>
                                <div class="vehicle-info">
                                    <strong><?= htmlspecialchars($vehicle['marca'] . ' ' . $vehicle['modelo']) ?></strong>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($vehicle['placa']) ?></td>
                            <td><?= htmlspecialchars($vehicle['ano']) ?></td>
                            <td><?= number_format($vehicle['km_atual'], 0, ',', '.') ?> km</td>
                            <td>
                                <span class="badge badge-<?= $vehicle['status'] === 'ativo' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($vehicle['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <?php endif; ?>

        <!-- Latest Maintenances Section -->
        <?php if (!empty($latestMaintenances)): ?>
        <section class="latest-section">
            <div class="section-header">
                <h2 class="section-title">Últimas Manutenções</h2>
                <a href="<?= BASE_URL ?>/cliente/manutencao" class="btn btn-link">Ver todas</a>
            </div>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Veículo</th>
                            <th>Data</th>
                            <th>Valor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latestMaintenances as $maintenance): ?>
                        <tr>
                            <td><?= htmlspecialchars($maintenance['tipo_manutencao']) ?></td>
                            <td><?= htmlspecialchars($maintenance['veiculo_modelo'] ?? 'N/A') ?></td>
                            <td><?= date('d/m/Y', strtotime($maintenance['data_manutencao'])) ?></td>
                            <td>R$ <?= number_format($maintenance['valor'], 2, ',', '.') ?></td>
                            <td>
                                <span class="badge badge-<?= $maintenance['status'] === 'concluido' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($maintenance['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <?php endif; ?>

        <!-- Empty State -->
        <?php if (empty($latestVehicles) && empty($latestMaintenances)): ?>
        <section class="empty-state">
            <div class="empty-icon">🚗</div>
            <h3 class="empty-title">Bem-vindo ao APP AUTO!</h3>
            <p class="empty-message">Comece cadastrando seu primeiro veículo para aproveitar todos os recursos.</p>
            <a href="<?= BASE_URL ?>/cliente/veiculos/novo" class="btn btn-primary">Cadastrar Veículo</a>
        </section>
        <?php endif; ?>
    </main>

    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
</body>
</html>
