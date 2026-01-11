<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar Manutenção - APP AUTO</title>
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
                <h1 class="page-title">Editar Manutenção</h1>
            </div>
            <div class="header-right">
                <a href="<?= BASE_URL ?>/cliente/manutencao" class="btn btn-secondary">
                    Voltar
                </a>
            </div>
        </header>

        <!-- Mensagens -->
        <div id="message-container"></div>

        <!-- Formulário -->
        <section class="form-section">
            <div class="form-card">
                <form id="maintenance-form" method="POST" action="<?= BASE_URL ?>/cliente/manutencao/<?= $maintenance['id'] ?>" data-method="PUT">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <input type="hidden" name="_method" value="PUT">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="veiculo_id">Veículo *</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($vehicle['marca'] . ' ' . $vehicle['modelo'] . ' - ' . $vehicle['placa']) ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="tipo_manutencao">Tipo de Manutenção *</label>
                            <select id="tipo_manutencao" name="tipo_manutencao" class="form-control" required>
                                <option value="">Selecione</option>
                                <option value="Troca de Óleo" <?= $maintenance['tipo_manutencao'] === 'Troca de Óleo' ? 'selected' : '' ?>>Troca de Óleo</option>
                                <option value="Troca de Filtros" <?= $maintenance['tipo_manutencao'] === 'Troca de Filtros' ? 'selected' : '' ?>>Troca de Filtros</option>
                                <option value="Troca de Pneus" <?= $maintenance['tipo_manutencao'] === 'Troca de Pneus' ? 'selected' : '' ?>>Troca de Pneus</option>
                                <option value="Revisão Geral" <?= $maintenance['tipo_manutencao'] === 'Revisão Geral' ? 'selected' : '' ?>>Revisão Geral</option>
                                <option value="Alinhamento e Balanceamento" <?= $maintenance['tipo_manutencao'] === 'Alinhamento e Balanceamento' ? 'selected' : '' ?>>Alinhamento e Balanceamento</option>
                                <option value="Freios" <?= $maintenance['tipo_manutencao'] === 'Freios' ? 'selected' : '' ?>>Freios</option>
                                <option value="Suspensão" <?= $maintenance['tipo_manutencao'] === 'Suspensão' ? 'selected' : '' ?>>Suspensão</option>
                                <option value="Elétrica" <?= $maintenance['tipo_manutencao'] === 'Elétrica' ? 'selected' : '' ?>>Elétrica</option>
                                <option value="Ar Condicionado" <?= $maintenance['tipo_manutencao'] === 'Ar Condicionado' ? 'selected' : '' ?>>Ar Condicionado</option>
                                <option value="Outro" <?= $maintenance['tipo_manutencao'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
                            </select>
                            <span class="error-message" id="tipo_manutencao-error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição *</label>
                        <textarea id="descricao" name="descricao" class="form-control" rows="3" required><?= htmlspecialchars($maintenance['descricao']) ?></textarea>
                        <span class="error-message" id="descricao-error"></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="data_manutencao">Data da Manutenção *</label>
                            <input type="date" id="data_manutencao" name="data_manutencao" class="form-control" value="<?= $maintenance['data_manutencao'] ?>" required>
                            <span class="error-message" id="data_manutencao-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="km_atual">KM Atual *</label>
                            <input type="text" id="km_atual" name="km_atual" class="form-control" value="<?= $maintenance['km_atual'] ?>" required>
                            <span class="error-message" id="km_atual-error"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="proxima_manutencao_km">Próxima Manutenção (KM)</label>
                            <input type="text" id="proxima_manutencao_km" name="proxima_manutencao_km" class="form-control" value="<?= $maintenance['proxima_manutencao_km'] ?? '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="custo">Custo (R$)</label>
                            <input type="text" id="custo" name="custo" class="form-control" value="<?= number_format($maintenance['custo'], 2, ',', '.') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="fornecedor">Fornecedor</label>
                            <input type="text" id="fornecedor" name="fornecedor" class="form-control" value="<?= htmlspecialchars($maintenance['fornecedor'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="concluida" <?= $maintenance['status'] === 'concluida' ? 'selected' : '' ?>>Concluída</option>
                                <option value="pendente" <?= $maintenance['status'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="<?= BASE_URL ?>/cliente/manutencao" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <span class="btn-text">Salvar Alterações</span>
                            <span class="btn-loading" style="display: none;">Salvando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/maintenance.js"></script>
</body>
</html>
