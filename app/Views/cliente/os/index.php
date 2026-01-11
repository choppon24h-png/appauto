<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordens de Serviço - APP AUTO</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/service-orders.css">
</head>
<body>
    <?php include APP_PATH . '/app/Views/cliente/_sidebar.php'; ?>
    <main class="main-content" id="main-content">
        <header class="content-header">
            <div class="header-left">
                <button class="mobile-menu-btn" id="mobile-menu-btn"><span></span><span></span><span></span></button>
                <h1 class="page-title">Ordens de Serviço</h1>
            </div>
        </header>
        <div id="message-container"></div>
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card"><div class="stat-icon">📋</div><div class="stat-value"><?= $stats['total'] ?></div><div class="stat-label">Total de O.S</div></div>
                <div class="stat-card"><div class="stat-icon">⏳</div><div class="stat-value"><?= $stats['pending'] ?></div><div class="stat-label">Pendentes</div></div>
                <div class="stat-card"><div class="stat-icon">🔧</div><div class="stat-value"><?= $stats['in_progress'] ?></div><div class="stat-label">Em Andamento</div></div>
                <div class="stat-card"><div class="stat-icon">✅</div><div class="stat-value"><?= $stats['completed'] ?></div><div class="stat-label">Concluídas</div></div>
            </div>
        </section>
        <?php if (!empty($inProgress)): ?>
        <section class="os-section">
            <h2 class="section-title">Em Andamento (<?= count($inProgress) ?>)</h2>
            <div class="os-list">
                <?php foreach ($inProgress as $os): ?>
                <div class="os-card in-progress">
                    <div class="os-header">
                        <span class="badge badge-info">Em Andamento</span>
                        <span class="os-number">#<?= str_pad($os['id'], 6, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="os-body">
                        <div class="os-info"><strong>Fornecedor:</strong> <?= htmlspecialchars($os['fornecedor_nome'] ?? 'N/A') ?></div>
                        <div class="os-info"><strong>Veículo:</strong> <?= htmlspecialchars($os['veiculo_placa'] ?? 'N/A') ?></div>
                        <div class="os-info"><strong>Data:</strong> <?= date('d/m/Y', strtotime($os['data_abertura'])) ?></div>
                    </div>
                    <div class="os-footer">
                        <a href="<?= BASE_URL ?>/cliente/os/<?= $os['id'] ?>" class="btn btn-primary btn-sm">Ver Detalhes</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        <?php if (!empty($completed)): ?>
        <section class="os-section">
            <h2 class="section-title">Concluídas (<?= count($completed) ?>)</h2>
            <div class="os-list">
                <?php foreach ($completed as $os): ?>
                <div class="os-card completed">
                    <div class="os-header">
                        <span class="badge badge-success">Concluída</span>
                        <span class="os-number">#<?= str_pad($os['id'], 6, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="os-body">
                        <div class="os-info"><strong>Fornecedor:</strong> <?= htmlspecialchars($os['fornecedor_nome'] ?? 'N/A') ?></div>
                        <div class="os-info"><strong>Valor:</strong> R$ <?= number_format($os['valor_total'], 2, ',', '.') ?></div>
                        <div class="os-info"><strong>Concluída em:</strong> <?= date('d/m/Y', strtotime($os['data_conclusao'])) ?></div>
                    </div>
                    <div class="os-footer">
                        <a href="<?= BASE_URL ?>/cliente/os/<?= $os['id'] ?>" class="btn btn-primary btn-sm">Ver Detalhes</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        <?php if (empty($pending) && empty($inProgress) && empty($completed)): ?>
        <section class="empty-state">
            <div class="empty-icon">📋</div>
            <h3 class="empty-title">Nenhuma ordem de serviço</h3>
            <p class="empty-message">Quando fornecedores realizarem serviços nos seus veículos, as O.S aparecerão aqui.</p>
        </section>
        <?php endif; ?>
    </main>
    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
</body>
</html>
