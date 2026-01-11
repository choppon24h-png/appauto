<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar Veículo - APP AUTO</title>
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
                <h1 class="page-title">Editar Veículo</h1>
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
                <form id="vehicle-form" method="POST" action="<?= BASE_URL ?>/cliente/veiculos/<?= $vehicle['id'] ?>" data-method="PUT">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    <input type="hidden" name="_method" value="PUT">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="marca">Marca *</label>
                            <input type="text" id="marca" name="marca" class="form-control" value="<?= htmlspecialchars($vehicle['marca']) ?>" required>
                            <span class="error-message" id="marca-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="modelo">Modelo *</label>
                            <input type="text" id="modelo" name="modelo" class="form-control" value="<?= htmlspecialchars($vehicle['modelo']) ?>" required>
                            <span class="error-message" id="modelo-error"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="ano">Ano *</label>
                            <input type="text" id="ano" name="ano" class="form-control" value="<?= htmlspecialchars($vehicle['ano']) ?>" maxlength="4" required>
                            <span class="error-message" id="ano-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="placa">Placa *</label>
                            <input type="text" id="placa" name="placa" class="form-control" value="<?= htmlspecialchars($vehicle['placa']) ?>" maxlength="8" required>
                            <span class="error-message" id="placa-error"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="cor">Cor</label>
                            <input type="text" id="cor" name="cor" class="form-control" value="<?= htmlspecialchars($vehicle['cor'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="combustivel">Combustível</label>
                            <select id="combustivel" name="combustivel" class="form-control">
                                <option value="">Selecione</option>
                                <option value="Gasolina" <?= $vehicle['combustivel'] === 'Gasolina' ? 'selected' : '' ?>>Gasolina</option>
                                <option value="Etanol" <?= $vehicle['combustivel'] === 'Etanol' ? 'selected' : '' ?>>Etanol</option>
                                <option value="Flex" <?= $vehicle['combustivel'] === 'Flex' ? 'selected' : '' ?>>Flex</option>
                                <option value="Diesel" <?= $vehicle['combustivel'] === 'Diesel' ? 'selected' : '' ?>>Diesel</option>
                                <option value="GNV" <?= $vehicle['combustivel'] === 'GNV' ? 'selected' : '' ?>>GNV</option>
                                <option value="Elétrico" <?= $vehicle['combustivel'] === 'Elétrico' ? 'selected' : '' ?>>Elétrico</option>
                                <option value="Híbrido" <?= $vehicle['combustivel'] === 'Híbrido' ? 'selected' : '' ?>>Híbrido</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="km_atual">KM Atual</label>
                            <input type="text" id="km_atual" name="km_atual" class="form-control" value="<?= htmlspecialchars($vehicle['km_atual']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="renavam">RENAVAM</label>
                            <input type="text" id="renavam" name="renavam" class="form-control" value="<?= htmlspecialchars($vehicle['renavam'] ?? '') ?>" maxlength="11">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="chassi">Chassi</label>
                            <input type="text" id="chassi" name="chassi" class="form-control" value="<?= htmlspecialchars($vehicle['chassi'] ?? '') ?>" maxlength="17">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="ativo" <?= $vehicle['status'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                                <option value="inativo" <?= $vehicle['status'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                                <option value="vendido" <?= $vehicle['status'] === 'vendido' ? 'selected' : '' ?>>Vendido</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="<?= BASE_URL ?>/cliente/veiculos" class="btn btn-secondary">Cancelar</a>
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
    <script src="<?= BASE_URL ?>/assets/js/vehicles.js"></script>
</body>
</html>
