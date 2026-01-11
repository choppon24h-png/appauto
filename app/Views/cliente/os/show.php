<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O.S #<?= str_pad($os['id'], 6, '0', STR_PAD_LEFT) ?> - APP AUTO</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/service-orders.css">
</head>
<body>
    <?php include APP_PATH . '/app/Views/cliente/_sidebar.php'; ?>
    <main class="main-content" id="main-content">
        <header class="content-header">
            <div class="header-left">
                <button class="mobile-menu-btn" id="mobile-menu-btn"><span></span><span></span><span></span></button>
                <h1 class="page-title">O.S #<?= str_pad($os['id'], 6, '0', STR_PAD_LEFT) ?></h1>
            </div>
            <div class="header-right">
                <a href="<?= BASE_URL ?>/cliente/os" class="btn btn-secondary">Voltar</a>
            </div>
        </header>
        <div id="message-container"></div>
        <section class="os-details">
            <div class="detail-card">
                <h3>Informações Gerais</h3>
                <div class="detail-grid">
                    <div class="detail-item"><span class="label">Status:</span> <span class="badge badge-<?= $os['status'] === 'concluida' ? 'success' : 'info' ?>"><?= ucfirst(str_replace('_', ' ', $os['status'])) ?></span></div>
                    <div class="detail-item"><span class="label">Fornecedor:</span> <?= htmlspecialchars($os['fornecedor_nome'] ?? 'N/A') ?></div>
                    <div class="detail-item"><span class="label">Veículo:</span> <?= htmlspecialchars($vehicle['marca'] . ' ' . $vehicle['modelo'] . ' - ' . $vehicle['placa']) ?></div>
                    <div class="detail-item"><span class="label">Data Abertura:</span> <?= date('d/m/Y H:i', strtotime($os['data_abertura'])) ?></div>
                    <?php if ($os['data_conclusao']): ?>
                    <div class="detail-item"><span class="label">Data Conclusão:</span> <?= date('d/m/Y H:i', strtotime($os['data_conclusao'])) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="detail-card">
                <h3>Serviços Realizados</h3>
                <div class="service-description"><?= nl2br(htmlspecialchars($os['descricao'])) ?></div>
            </div>
            <div class="detail-card">
                <h3>Valores</h3>
                <div class="value-grid">
                    <div class="value-item"><span class="label">Mão de Obra:</span> <span class="value">R$ <?= number_format($os['valor_mao_obra'] ?? 0, 2, ',', '.') ?></span></div>
                    <div class="value-item"><span class="label">Peças:</span> <span class="value">R$ <?= number_format($os['valor_pecas'] ?? 0, 2, ',', '.') ?></span></div>
                    <div class="value-item total"><span class="label">Total:</span> <span class="value">R$ <?= number_format($os['valor_total'], 2, ',', '.') ?></span></div>
                </div>
            </div>
            <?php if ($os['status'] === 'concluida' && $os['certificado_appauto']): ?>
            <div class="detail-card certificate">
                <h3>✓ Certificado APP AUTO</h3>
                <p>Este serviço foi certificado pela plataforma APP AUTO.</p>
                <div class="certificate-code">Código: <?= $os['certificado_appauto'] ?></div>
            </div>
            <?php endif; ?>
            <?php if ($os['status'] === 'concluida' && !$os['avaliacao']): ?>
            <div class="detail-card rating">
                <h3>Avaliar Serviço</h3>
                <form id="rating-form" data-os-id="<?= $os['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <div class="rating-stars">
                        <span class="star" data-rating="1">★</span>
                        <span class="star" data-rating="2">★</span>
                        <span class="star" data-rating="3">★</span>
                        <span class="star" data-rating="4">★</span>
                        <span class="star" data-rating="5">★</span>
                    </div>
                    <input type="hidden" name="rating" id="rating-value">
                    <textarea name="comment" class="form-control" placeholder="Comentário (opcional)" rows="3"></textarea>
                    <button type="submit" class="btn btn-primary">Enviar Avaliação</button>
                </form>
            </div>
            <?php elseif ($os['avaliacao']): ?>
            <div class="detail-card rating-display">
                <h3>Sua Avaliação</h3>
                <div class="rating-stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star <?= $i <= $os['avaliacao'] ? 'active' : '' ?>">★</span>
                    <?php endfor; ?>
                </div>
                <?php if ($os['comentario_avaliacao']): ?>
                <p class="rating-comment"><?= htmlspecialchars($os['comentario_avaliacao']) ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </section>
    </main>
    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/service-orders.js"></script>
</body>
</html>
