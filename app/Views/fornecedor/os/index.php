<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ordens de Serviço - APP AUTO</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/provider.css">
</head>
<body>
<?php include APP_PATH.'/app/Views/fornecedor/_sidebar.php';?>
<main class="main-content" id="main-content">
<header class="content-header">
<div class="header-left">
<button class="mobile-menu-btn" id="mobile-menu-btn"><span></span><span></span><span></span></button>
<h1 class="page-title">Ordens de Serviço</h1>
</div>
<div class="header-right">
<a href="<?= BASE_URL ?>/fornecedor/os/criar" class="btn btn-primary">Nova O.S</a>
</div>
</header>
<div id="message-container"></div>
<section class="os-section">
<?php if(!empty($orders)):?>
<div class="table-responsive">
<table class="data-table">
<thead>
<tr><th>#</th><th>Cliente</th><th>Veículo</th><th>Status</th><th>Valor</th><th>Data</th><th>Ações</th></tr>
</thead>
<tbody>
<?php foreach($orders as $os):?>
<tr>
<td>#<?= str_pad($os['id'],6,'0',STR_PAD_LEFT)?></td>
<td><?= htmlspecialchars($os['cliente_nome']??'N/A')?></td>
<td><?= htmlspecialchars($os['veiculo_placa']??'N/A')?></td>
<td><span class="badge badge-<?= $os['status']==='concluida'?'success':($os['status']==='em_andamento'?'info':'warning')?>"><?= ucfirst(str_replace('_',' ',$os['status']))?></span></td>
<td>R$ <?= number_format($os['valor_total'],2,',','.')?></td>
<td><?= date('d/m/Y',strtotime($os['data_abertura']))?></td>
<td><a href="<?= BASE_URL ?>/fornecedor/os/<?= $os['id']?>" class="btn btn-sm btn-primary">Ver</a></td>
</tr>
<?php endforeach;?>
</tbody>
</table>
</div>
<?php else:?>
<div class="empty-state">
<div class="empty-icon">📋</div>
<h3 class="empty-title">Nenhuma O.S registrada</h3>
<p class="empty-message">Crie sua primeira ordem de serviço.</p>
<a href="<?= BASE_URL ?>/fornecedor/os/criar" class="btn btn-primary">Criar Primeira O.S</a>
</div>
<?php endif;?>
</section>
</main>
<script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
</body>
</html>
