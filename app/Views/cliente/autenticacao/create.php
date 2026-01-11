<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Token - APP AUTO</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/authentication.css">
</head>
<body>
    <?php include APP_PATH . '/app/Views/cliente/_sidebar.php'; ?>
    <main class="main-content" id="main-content">
        <header class="content-header">
            <div class="header-left">
                <button class="mobile-menu-btn" id="mobile-menu-btn"><span></span><span></span><span></span></button>
                <h1 class="page-title">Gerar Token de Acesso</h1>
            </div>
            <div class="header-right">
                <a href="<?= BASE_URL ?>/cliente/autenticacao" class="btn btn-secondary">Voltar</a>
            </div>
        </header>
        <div id="message-container"></div>
        <section class="form-section">
            <div class="form-card">
                <form id="generate-form" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <div class="form-group">
                        <label for="veiculo_id">Veículo *</label>
                        <select id="veiculo_id" name="veiculo_id" class="form-control" required>
                            <option value="">Selecione um veículo</option>
                            <?php foreach ($vehicles as $vehicle): ?>
                            <option value="<?= $vehicle['id'] ?>"><?= htmlspecialchars($vehicle['marca'] . ' ' . $vehicle['modelo'] . ' - ' . $vehicle['placa']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error-message" id="veiculo_id-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="segmento">Segmento do Fornecedor *</label>
                        <select id="segmento" name="segmento" class="form-control" required>
                            <option value="">Selecione</option>
                            <option value="Oficina Mecânica">Oficina Mecânica</option>
                            <option value="Funilaria e Pintura">Funilaria e Pintura</option>
                            <option value="Elétrica Automotiva">Elétrica Automotiva</option>
                            <option value="Borracharia">Borracharia</option>
                            <option value="Lava Rápido">Lava Rápido</option>
                            <option value="Despachante">Despachante</option>
                            <option value="Seguradora">Seguradora</option>
                            <option value="Concessionária">Concessionária</option>
                            <option value="Autopeças">Autopeças</option>
                            <option value="Guincho">Guincho</option>
                            <option value="Vistoria">Vistoria</option>
                            <option value="Instalação de Acessórios">Instalação de Acessórios</option>
                            <option value="Ar Condicionado">Ar Condicionado</option>
                            <option value="Alinhamento e Balanceamento">Alinhamento e Balanceamento</option>
                            <option value="Vidraçaria">Vidraçaria</option>
                            <option value="Estética Automotiva">Estética Automotiva</option>
                            <option value="Outro">Outro</option>
                        </select>
                        <span class="error-message" id="segmento-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea id="observacoes" name="observacoes" class="form-control" rows="3" placeholder="Informações adicionais sobre o acesso"></textarea>
                    </div>
                    <div class="form-actions">
                        <a href="<?= BASE_URL ?>/cliente/autenticacao" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <span class="btn-text">Gerar Token</span>
                            <span class="btn-loading" style="display:none;">Gerando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <div class="modal" id="token-modal" style="display:none;">
        <div class="modal-overlay" onclick="closeTokenModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Token Gerado com Sucesso!</h3>
                <button class="modal-close" onclick="closeTokenModal()">×</button>
            </div>
            <div class="modal-body">
                <div class="token-display">
                    <div class="token-label">Token de Acesso:</div>
                    <div class="token-value" id="token-value">000000</div>
                    <button class="btn btn-secondary btn-sm" onclick="copyToken()">Copiar Token</button>
                </div>
                <div class="token-info">
                    <p><strong>Validade:</strong> <span id="token-expiry"></span></p>
                    <p class="text-muted">Compartilhe este token com o fornecedor para que ele possa acessar os dados do veículo.</p>
                </div>
            </div>
            <div class="modal-footer">
                <a href="<?= BASE_URL ?>/cliente/autenticacao" class="btn btn-primary">Ver Autenticações</a>
            </div>
        </div>
    </div>
    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/authentication.js"></script>
</body>
</html>
