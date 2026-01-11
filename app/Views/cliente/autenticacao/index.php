<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticação de Fornecedores - APP AUTO</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/authentication.css">
</head>
<body>
    <?php include APP_PATH . '/app/Views/cliente/_sidebar.php'; ?>
    <main class="main-content" id="main-content">
        <header class="content-header">
            <div class="header-left">
                <button class="mobile-menu-btn" id="mobile-menu-btn"><span></span><span></span><span></span></button>
                <h1 class="page-title">Autenticação de Fornecedores</h1>
            </div>
            <div class="header-right">
                <a href="<?= BASE_URL ?>/cliente/autenticacao/gerar" class="btn btn-primary">Gerar Token</a>
            </div>
        </header>
        <div id="message-container"></div>
        
        <?php if (!empty($pending)): ?>
        <section class="auth-section">
            <h2 class="section-title">Solicitações Pendentes (<?= count($pending) ?>)</h2>
            <div class="auth-list">
                <?php foreach ($pending as $auth): ?>
                <div class="auth-card pending" data-auth-id="<?= $auth['id'] ?>">
                    <div class="auth-header">
                        <span class="badge badge-warning">Pendente</span>
                        <span class="auth-token"><?= $auth['token'] ?></span>
                    </div>
                    <div class="auth-body">
                        <div class="auth-info">
                            <span class="label">Segmento:</span> <?= htmlspecialchars($auth['segmento']) ?>
                        </div>
                        <div class="auth-info">
                            <span class="label">Gerado em:</span> <?= date('d/m/Y H:i', strtotime($auth['data_geracao'])) ?>
                        </div>
                        <div class="auth-info">
                            <span class="label">Expira em:</span> <?= date('d/m/Y H:i', strtotime($auth['expira_em'])) ?>
                        </div>
                    </div>
                    <div class="auth-actions">
                        <button class="btn btn-success btn-sm" onclick="approveAuth(<?= $auth['id'] ?>)">Aprovar</button>
                        <button class="btn btn-danger btn-sm" onclick="denyAuth(<?= $auth['id'] ?>)">Negar</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <?php if (!empty($approved)): ?>
        <section class="auth-section">
            <h2 class="section-title">Acessos Aprovados (<?= count($approved) ?>)</h2>
            <div class="auth-list">
                <?php foreach ($approved as $auth): ?>
                <div class="auth-card approved" data-auth-id="<?= $auth['id'] ?>">
                    <div class="auth-header">
                        <span class="badge badge-success">Aprovado</span>
                        <span class="auth-token"><?= $auth['token'] ?></span>
                    </div>
                    <div class="auth-body">
                        <div class="auth-info">
                            <span class="label">Fornecedor:</span> <?= htmlspecialchars($auth['fornecedor_nome'] ?? 'Não identificado') ?>
                        </div>
                        <div class="auth-info">
                            <span class="label">Segmento:</span> <?= htmlspecialchars($auth['segmento']) ?>
                        </div>
                        <div class="auth-info">
                            <span class="label">Aprovado em:</span> <?= date('d/m/Y H:i', strtotime($auth['data_aprovacao'])) ?>
                        </div>
                    </div>
                    <div class="auth-actions">
                        <button class="btn btn-warning btn-sm" onclick="revokeAuth(<?= $auth['id'] ?>)">Revogar</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteAuth(<?= $auth['id'] ?>)">Excluir</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
        
        <?php if (empty($pending) && empty($approved) && empty($denied) && empty($expired)): ?>
        <section class="empty-state">
            <div class="empty-icon">🔐</div>
            <h3 class="empty-title">Nenhuma autenticação registrada</h3>
            <p class="empty-message">Gere tokens para permitir que fornecedores acessem os dados dos seus veículos.</p>
            <a href="<?= BASE_URL ?>/cliente/autenticacao/gerar" class="btn btn-primary">Gerar Primeiro Token</a>
        </section>
        <?php endif; ?>
    </main>
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/authentication.js"></script>
</body>
</html>
