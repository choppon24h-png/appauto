<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Meus Veículos - APP AUTO</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/vehicles.css">
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
                <h1 class="page-title">Meus Veículos</h1>
            </div>
            <div class="header-right">
                <a href="<?= BASE_URL ?>/cliente/veiculos/novo" class="btn btn-primary">
                    Cadastrar Veículo
                </a>
            </div>
        </header>

        <!-- Mensagens -->
        <div id="message-container"></div>

        <!-- Lista de Veículos -->
        <?php if (!empty($vehicles)): ?>
        <section class="vehicles-section">
            <div class="vehicles-grid">
                <?php foreach ($vehicles as $vehicle): ?>
                <div class="vehicle-card" data-vehicle-id="<?= $vehicle['id'] ?>">
                    <div class="vehicle-header">
                        <div class="vehicle-title">
                            <h3><?= htmlspecialchars($vehicle['marca'] . ' ' . $vehicle['modelo']) ?></h3>
                            <span class="badge badge-<?= $vehicle['status'] === 'ativo' ? 'success' : 'secondary' ?>">
                                <?= ucfirst($vehicle['status']) ?>
                            </span>
                        </div>
                        <div class="vehicle-actions">
                            <button class="btn-icon" onclick="editVehicle(<?= $vehicle['id'] ?>)" title="Editar">
                                ✏️
                            </button>
                            <button class="btn-icon btn-danger" onclick="deleteVehicle(<?= $vehicle['id'] ?>, '<?= htmlspecialchars($vehicle['modelo']) ?>')" title="Excluir">
                                🗑️
                            </button>
                        </div>
                    </div>

                    <div class="vehicle-body">
                        <div class="vehicle-info-grid">
                            <div class="info-item">
                                <span class="info-label">Placa</span>
                                <span class="info-value"><?= htmlspecialchars($vehicle['placa']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Ano</span>
                                <span class="info-value"><?= htmlspecialchars($vehicle['ano']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Cor</span>
                                <span class="info-value"><?= htmlspecialchars($vehicle['cor'] ?: 'N/A') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Combustível</span>
                                <span class="info-value"><?= htmlspecialchars($vehicle['combustivel'] ?: 'N/A') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">KM Atual</span>
                                <span class="info-value"><?= number_format($vehicle['km_atual'], 0, ',', '.') ?> km</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Cadastrado em</span>
                                <span class="info-value"><?= date('d/m/Y', strtotime($vehicle['data_criacao'])) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="vehicle-footer">
                        <a href="<?= BASE_URL ?>/cliente/veiculos/<?= $vehicle['id'] ?>" class="btn btn-link">
                            Ver Detalhes
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php else: ?>
        <!-- Empty State -->
        <section class="empty-state">
            <div class="empty-icon">🚗</div>
            <h3 class="empty-title">Nenhum veículo cadastrado</h3>
            <p class="empty-message">Comece cadastrando seu primeiro veículo para aproveitar todos os recursos do APP AUTO.</p>
            <a href="<?= BASE_URL ?>/cliente/veiculos/novo" class="btn btn-primary">Cadastrar Primeiro Veículo</a>
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
                <p>Tem certeza que deseja excluir o veículo <strong id="vehicle-name"></strong>?</p>
                <p class="text-danger">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancelar</button>
                <button class="btn btn-danger" id="confirm-delete-btn">Excluir</button>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vehicles.js"></script>
</body>
</html>
