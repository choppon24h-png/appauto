<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consulta de Veículos - APP AUTO</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/provider.css">
</head>
<body>
<?php include APP_PATH.'/app/Views/fornecedor/_sidebar.php';?>
<main class="main-content" id="main-content">
<header class="content-header">
<div class="header-left">
<button class="mobile-menu-btn" id="mobile-menu-btn"><span></span><span></span><span></span></button>
<h1 class="page-title">Consulta de Veículos</h1>
</div>
</header>
<div id="message-container"></div>
<section class="search-section">
<div class="search-card">
<h3>Buscar Veículo por Placa</h3>
<form id="search-form">
<div class="search-input-group">
<input type="text" id="placa" name="placa" class="form-control" placeholder="ABC-1234" maxlength="8" required>
<button type="submit" class="btn btn-primary">Buscar</button>
</div>
<small>Digite a placa do veículo para consultar informações</small>
</form>
</div>
</section>
<section class="result-section" id="result-section" style="display:none;">
<div class="vehicle-card">
<div class="vehicle-header">
<h3 id="vehicle-title"></h3>
<a id="view-details-btn" class="btn btn-primary">Ver Detalhes Completos</a>
</div>
<div class="vehicle-info-grid" id="vehicle-info"></div>
</div>
</section>
</main>
<script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
<script src="<?= BASE_URL ?>/assets/js/provider-vehicles.js"></script>
</body>
</html>
