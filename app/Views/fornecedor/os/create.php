<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nova O.S - APP AUTO</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/provider.css">
</head>
<body>
<?php include APP_PATH.'/app/Views/fornecedor/_sidebar.php';?>
<main class="main-content" id="main-content">
<header class="content-header">
<div class="header-left">
<button class="mobile-menu-btn" id="mobile-menu-btn"><span></span><span></span><span></span></button>
<h1 class="page-title">Nova Ordem de Serviço</h1>
</div>
<div class="header-right">
<a href="<?= BASE_URL ?>/fornecedor/os" class="btn btn-secondary">Voltar</a>
</div>
</header>
<div id="message-container"></div>
<section class="form-section">
<form id="os-form" class="os-form">
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']??''?>">
<div class="form-card">
<h3>Cliente e Veículo</h3>
<div class="form-row">
<div class="form-group">
<label>Cliente *</label>
<select name="cliente_id" id="cliente-select" class="form-control" required>
<option value="">Selecione o cliente</option>
<?php foreach($clients as $client):?>
<option value="<?= $client['id']?>"><?= htmlspecialchars($client['nome'])?></option>
<?php endforeach;?>
</select>
</div>
<div class="form-group">
<label>Veículo *</label>
<select name="veiculo_id" id="veiculo-select" class="form-control" required <?= $selectedVehicle?'':'disabled'?>>
<option value="">Selecione o veículo</option>
<?php if($selectedVehicle):?>
<option value="<?= $selectedVehicle['id']?>" selected><?= htmlspecialchars($selectedVehicle['marca'].' '.$selectedVehicle['modelo'].' - '.$selectedVehicle['placa'])?></option>
<?php endif;?>
</select>
</div>
</div>
</div>
<div class="form-card">
<h3>Descrição do Serviço</h3>
<div class="form-group">
<label>Descrição *</label>
<textarea name="descricao" class="form-control" rows="5" required placeholder="Descreva os serviços a serem realizados..."></textarea>
</div>
</div>
<div class="form-card">
<h3>Valores</h3>
<div class="form-row">
<div class="form-group">
<label>Mão de Obra (R$)</label>
<input type="number" name="valor_mao_obra" id="valor-mao-obra" class="form-control" step="0.01" min="0" value="0">
</div>
<div class="form-group">
<label>Peças (R$)</label>
<input type="number" name="valor_pecas" id="valor-pecas" class="form-control" step="0.01" min="0" value="0">
</div>
<div class="form-group">
<label>Total (R$)</label>
<input type="text" id="valor-total" class="form-control" readonly value="R$ 0,00">
</div>
</div>
</div>
<div class="form-card">
<h3>Status Inicial</h3>
<div class="form-group">
<label>Status *</label>
<select name="status" class="form-control" required>
<option value="pendente">Pendente</option>
<option value="em_andamento" selected>Em Andamento</option>
</select>
</div>
</div>
<div class="form-actions">
<button type="submit" class="btn btn-primary btn-lg">Criar O.S</button>
</div>
</form>
</section>
</main>
<script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
<script src="<?= BASE_URL ?>/assets/js/provider-os.js"></script>
</body>
</html>
