<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clientes - APP AUTO</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/provider.css">
</head>
<body>
<?php include APP_PATH.'/app/Views/fornecedor/_sidebar.php';?>
<main class="main-content" id="main-content">
<header class="content-header">
<div class="header-left">
<button class="mobile-menu-btn" id="mobile-menu-btn"><span></span><span></span><span></span></button>
<h1 class="page-title">Clientes</h1>
</div>
<div class="header-right">
<button class="btn btn-primary" onclick="openAddClientModal()">Adicionar Cliente</button>
</div>
</header>
<div id="message-container"></div>
<section class="clients-section">
<?php if(!empty($clients)):?>
<div class="table-responsive">
<table class="data-table">
<thead>
<tr><th>Nome</th><th>Email</th><th>Telefone</th><th>Veículos</th><th>Ações</th></tr>
</thead>
<tbody>
<?php foreach($clients as $client):?>
<tr>
<td><?= htmlspecialchars($client['nome'])?></td>
<td><?= htmlspecialchars($client['email'])?></td>
<td><?= htmlspecialchars($client['telefone']??'N/A')?></td>
<td><?= $client['total_veiculos']??0?></td>
<td>
<button class="btn btn-sm btn-danger" onclick="removeClient(<?= $client['id']?>,'<?= htmlspecialchars($client['nome'])?>')">Remover</button>
</td>
</tr>
<?php endforeach;?>
</tbody>
</table>
</div>
<?php else:?>
<div class="empty-state">
<div class="empty-icon">👥</div>
<h3 class="empty-title">Nenhum cliente cadastrado</h3>
<p class="empty-message">Adicione clientes para começar a gerenciar seus serviços.</p>
<button class="btn btn-primary" onclick="openAddClientModal()">Adicionar Primeiro Cliente</button>
</div>
<?php endif;?>
</section>
</main>
<div class="modal" id="add-client-modal">
<div class="modal-content">
<div class="modal-header">
<h3>Adicionar Cliente</h3>
<button class="modal-close" onclick="closeAddClientModal()">&times;</button>
</div>
<div class="modal-body">
<form id="add-client-form">
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']??''?>">
<div class="form-group">
<label>Email do Cliente</label>
<input type="email" name="email" class="form-control" required placeholder="cliente@email.com">
<small>O cliente já deve estar cadastrado no APP AUTO</small>
</div>
<button type="submit" class="btn btn-primary btn-block">Adicionar</button>
</form>
</div>
</div>
</div>
<script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
<script src="<?= BASE_URL ?>/assets/js/provider-clients.js"></script>
</body>
</html>
