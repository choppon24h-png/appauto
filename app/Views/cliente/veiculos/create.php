<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastrar Veículo - APP AUTO</title>
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
                <h1 class="page-title">Cadastrar Veículo</h1>
            </div>
            <div class="header-right">
                <a href="<?= BASE_URL ?>/cliente/veiculos" class="btn btn-secondary">
                    Voltar
                </a>
            </div>
        </header>

        <!-- Mensagens -->
        <div id="message-container"></div>

        <!-- Formulário -->
        <section class="form-section">
            <div class="form-card">
                <form id="vehicle-form" method="POST" action="<?= BASE_URL ?>/cliente/veiculos">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="marca">Marca *</label>
                            <input type="text" id="marca" name="marca" class="form-control" required>
                            <span class="error-message" id="marca-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="modelo">Modelo *</label>
                            <input type="text" id="modelo" name="modelo" class="form-control" required>
                            <span class="error-message" id="modelo-error"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ano">Ano *</label>
                            <input type="text" id="ano" name="ano" class="form-control" placeholder="2024" maxlength="4" required>
                            <span class="error-message" id="ano-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="placa">Placa *</label>
                            <input type="text" id="placa" name="placa" class="form-control" placeholder="ABC1234" maxlength="8" required>
                            <span class="error-message" id="placa-error"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="cor">Cor</label>
                            <input type="text" id="cor" name="cor" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="combustivel">Combustível</label>
                            <select id="combustivel" name="combustivel" class="form-control">
                                <option value="">Selecione</option>
                                <option value="Gasolina">Gasolina</option>
                                <option value="Etanol">Etanol</option>
                                <option value="Flex">Flex</option>
                                <option value="Diesel">Diesel</option>
                                <option value="GNV">GNV</option>
                                <option value="Elétrico">Elétrico</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="km_atual">KM Atual</label>
                            <input type="text" id="km_atual" name="km_atual" class="form-control" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for="renavam">RENAVAM</label>
                            <input type="text" id="renavam" name="renavam" class="form-control" maxlength="11">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="chassi">Chassi</label>
                        <input type="text" id="chassi" name="chassi" class="form-control" maxlength="17">
                    </div>

                    <div class="form-actions">
                        <a href="<?= BASE_URL ?>/cliente/veiculos" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <span class="btn-text">Cadastrar Veículo</span>
                            <span class="btn-loading" style="display: none;">Cadastrando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/vehicles.js"></script>
</body>
</html>
