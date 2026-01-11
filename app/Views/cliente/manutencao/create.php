<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrar Manutenção - APP AUTO</title>
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
                <h1 class="page-title">Registrar Manutenção</h1>
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
                <form id="maintenance-form" method="POST" action="<?= BASE_URL ?>/cliente/manutencao">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="veiculo_id">Veículo *</label>
                            <select id="veiculo_id" name="veiculo_id" class="form-control" required>
                                <option value="">Selecione um veículo</option>
                                <?php foreach ($vehicles as $vehicle): ?>
                                <option value="<?= $vehicle['id'] ?>" data-km="<?= $vehicle['km_atual'] ?>">
                                    <?= htmlspecialchars($vehicle['marca'] . ' ' . $vehicle['modelo'] . ' - ' . $vehicle['placa']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="error-message" id="veiculo_id-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="tipo_manutencao">Tipo de Manutenção *</label>
                            <select id="tipo_manutencao" name="tipo_manutencao" class="form-control" required>
                                <option value="">Selecione</option>
                                <option value="Troca de Óleo">Troca de Óleo</option>
                                <option value="Troca de Filtros">Troca de Filtros</option>
                                <option value="Troca de Pneus">Troca de Pneus</option>
                                <option value="Revisão Geral">Revisão Geral</option>
                                <option value="Alinhamento e Balanceamento">Alinhamento e Balanceamento</option>
                                <option value="Freios">Freios</option>
                                <option value="Suspensão">Suspensão</option>
                                <option value="Elétrica">Elétrica</option>
                                <option value="Ar Condicionado">Ar Condicionado</option>
                                <option value="Outro">Outro</option>
                            </select>
                            <span class="error-message" id="tipo_manutencao-error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição *</label>
                        <textarea id="descricao" name="descricao" class="form-control" rows="3" required placeholder="Descreva o serviço realizado"></textarea>
                        <span class="error-message" id="descricao-error"></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="data_manutencao">Data da Manutenção *</label>
                            <input type="date" id="data_manutencao" name="data_manutencao" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            <span class="error-message" id="data_manutencao-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="km_atual">KM Atual *</label>
                            <input type="text" id="km_atual" name="km_atual" class="form-control" placeholder="0" required>
                            <span class="error-message" id="km_atual-error"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="proxima_manutencao_km">Próxima Manutenção (KM)</label>
                            <input type="text" id="proxima_manutencao_km" name="proxima_manutencao_km" class="form-control" placeholder="0">
                            <span class="form-hint">Deixe em branco se não aplicável</span>
                        </div>

                        <div class="form-group">
                            <label for="custo">Custo (R$)</label>
                            <input type="text" id="custo" name="custo" class="form-control" placeholder="0,00">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="fornecedor">Fornecedor</label>
                            <input type="text" id="fornecedor" name="fornecedor" class="form-control" placeholder="Nome da oficina ou mecânico">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="concluida" selected>Concluída</option>
                                <option value="pendente">Pendente</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="<?= BASE_URL ?>/cliente/manutencao" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <span class="btn-text">Registrar Manutenção</span>
                            <span class="btn-loading" style="display: none;">Registrando...</span>
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
