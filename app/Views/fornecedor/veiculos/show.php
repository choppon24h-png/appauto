<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Veículo <?= htmlspecialchars($vehicle['placa'])?> - APP AUTO</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/provider.css">
</head>
<body>
<?php include APP_PATH.'/app/Views/fornecedor/_sidebar.php';?>
<main class="main-content" id="main-content">
<header class="content-header">
<div class="header-left">
<button class="mobile-menu-btn" id="mobile-menu-btn"><span></span><span></span><span></span></button>
<h1 class="page-title"><?= htmlspecialchars($vehicle['marca'].' '.$vehicle['modelo'])?></h1>
</div>
<div class="header-right">
<a href="<?= BASE_URL ?>/fornecedor/veiculos" class="btn btn-secondary">Voltar</a>
<a href="<?= BASE_URL ?>/fornecedor/os/criar?veiculo=<?= $vehicle['id']?>" class="btn btn-primary">Criar O.S</a>
</div>
</header>
<section class="vehicle-details">
<div class="detail-card">
<h3>Informações do Veículo</h3>
<div class="detail-grid">
<div class="detail-item"><span class="label">Placa:</span> <?= htmlspecialchars($vehicle['placa'])?></div>
<div class="detail-item"><span class="label">Marca:</span> <?= htmlspecialchars($vehicle['marca'])?></div>
<div class="detail-item"><span class="label">Modelo:</span> <?= htmlspecialchars($vehicle['modelo'])?></div>
<div class="detail-item"><span class="label">Ano:</span> <?= htmlspecialchars($vehicle['ano'])?></div>
<div class="detail-item"><span class="label">Cor:</span> <?= htmlspecialchars($vehicle['cor'])?></div>
<div class="detail-item"><span class="label">Combustível:</span> <?= htmlspecialchars($vehicle['combustivel'])?></div>
<div class="detail-item"><span class="label">KM Atual:</span> <?= number_format($vehicle['km_atual'],0,'','.')?></div>
<div class="detail-item"><span class="label">Chassi:</span> <?= htmlspecialchars($vehicle['chassi']??'N/A')?></div>
</div>
</div>
<div class="detail-card">
<h3>Histórico de Manutenções (<?= count($maintenances)?>)</h3>
<?php if(!empty($maintenances)):?>
<div class="maintenance-list">
<?php foreach($maintenances as $m):?>
<div class="maintenance-item">
<div class="maintenance-header">
<strong><?= htmlspecialchars($m['tipo'])?></strong>
<span class="badge badge-<?= $m['status']==='concluida'?'success':'warning'?>"><?= ucfirst($m['status'])?></span>
</div>
<div class="maintenance-body">
<div>Data: <?= date('d/m/Y',strtotime($m['data_manutencao']))?></div>
<div>KM: <?= number_format($m['km_atual'],0,'','.')?></div>
<?php if($m['custo']):?><div>Custo: R$ <?= number_format($m['custo'],2,',','.')?></div><?php endif;?>
</div>
</div>
<?php endforeach;?>
</div>
<?php else:?>
<p class="empty-message">Nenhuma manutenção registrada</p>
<?php endif;?>
</div>
<div class="detail-card">
<h3>Documentos da Carteira (<?= count($documents)?>)</h3>
<?php if(!empty($documents)):?>
<div class="documents-grid">
<?php foreach($documents as $doc):?>
<div class="document-item">
<div class="document-icon">📄</div>
<div class="document-info">
<strong><?= htmlspecialchars($doc['tipo_documento'])?></strong>
<?php if($doc['data_vencimento']):?>
<small>Vence em: <?= date('d/m/Y',strtotime($doc['data_vencimento']))?></small>
<?php endif;?>
</div>
</div>
<?php endforeach;?>
</div>
<?php else:?>
<p class="empty-message">Nenhum documento cadastrado</p>
<?php endif;?>
</div>
</section>
</main>
<script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
</body>
</html>
