<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>O.S #<?= str_pad($os['id'],6,'0',STR_PAD_LEFT)?> - APP AUTO</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/provider.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/service-orders.css">
</head>
<body>
<?php include APP_PATH.'/app/Views/fornecedor/_sidebar.php';?>
<main class="main-content" id="main-content">
<header class="content-header">
<div class="header-left">
<button class="mobile-menu-btn" id="mobile-menu-btn"><span></span><span></span><span></span></button>
<h1 class="page-title">O.S #<?= str_pad($os['id'],6,'0',STR_PAD_LEFT)?></h1>
</div>
<div class="header-right">
<a href="<?= BASE_URL ?>/fornecedor/os" class="btn btn-secondary">Voltar</a>
<?php if($os['status']!=='concluida'):?>
<button onclick="openCompleteModal()" class="btn btn-success">Finalizar Serviço</button>
<?php endif;?>
</div>
</header>
<div id="message-container"></div>
<section class="os-details">
<div class="detail-card">
<h3>Informações Gerais</h3>
<div class="detail-grid">
<div class="detail-item"><span class="label">Status:</span> <span class="badge badge-<?= $os['status']==='concluida'?'success':($os['status']==='em_andamento'?'info':'warning')?>"><?= ucfirst(str_replace('_',' ',$os['status']))?></span></div>
<div class="detail-item"><span class="label">Data Abertura:</span> <?= date('d/m/Y H:i',strtotime($os['data_abertura']))?></div>
<?php if($os['data_conclusao']):?>
<div class="detail-item"><span class="label">Data Conclusão:</span> <?= date('d/m/Y H:i',strtotime($os['data_conclusao']))?></div>
<?php endif;?>
</div>
</div>
<div class="detail-card">
<h3>Cliente e Veículo</h3>
<div class="detail-grid">
<div class="detail-item"><span class="label">Cliente:</span> <?= htmlspecialchars($client['nome'])?></div>
<div class="detail-item"><span class="label">Email:</span> <?= htmlspecialchars($client['email'])?></div>
<div class="detail-item"><span class="label">Telefone:</span> <?= htmlspecialchars($client['telefone']??'N/A')?></div>
<div class="detail-item"><span class="label">Veículo:</span> <?= htmlspecialchars($vehicle['marca'].' '.$vehicle['modelo'])?></div>
<div class="detail-item"><span class="label">Placa:</span> <?= htmlspecialchars($vehicle['placa'])?></div>
<div class="detail-item"><span class="label">Ano:</span> <?= htmlspecialchars($vehicle['ano'])?></div>
</div>
</div>
<div class="detail-card">
<h3>Descrição do Serviço</h3>
<p><?= nl2br(htmlspecialchars($os['descricao']))?></p>
</div>
<div class="detail-card">
<h3>Valores</h3>
<div class="detail-grid">
<div class="detail-item"><span class="label">Mão de Obra:</span> R$ <?= number_format($os['valor_mao_obra'],2,',','.')?></div>
<div class="detail-item"><span class="label">Peças:</span> R$ <?= number_format($os['valor_pecas'],2,',','.')?></div>
<div class="detail-item"><span class="label">Total:</span> <strong>R$ <?= number_format($os['valor_total'],2,',','.')?></strong></div>
</div>
</div>
<?php if($os['status']==='concluida'&&$os['certificado_codigo']):?>
<div class="detail-card certificate-card">
<h3>Certificado APP AUTO</h3>
<div class="certificate-code"><?= htmlspecialchars($os['certificado_codigo'])?></div>
<p class="certificate-text">Este serviço foi certificado pelo APP AUTO</p>
</div>
<?php endif;?>
<?php if($os['observacoes_finais']):?>
<div class="detail-card">
<h3>Observações Finais</h3>
<p><?= nl2br(htmlspecialchars($os['observacoes_finais']))?></p>
</div>
<?php endif;?>
</section>
</main>
<div class="modal" id="complete-modal">
<div class="modal-content">
<div class="modal-header">
<h3>Finalizar Serviço</h3>
<button class="modal-close" onclick="closeCompleteModal()">&times;</button>
</div>
<div class="modal-body">
<form id="complete-form">
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']??''?>">
<div class="form-group">
<label>Observações Finais</label>
<textarea name="observacoes" class="form-control" rows="4" placeholder="Adicione observações sobre o serviço realizado..."></textarea>
</div>
<button type="submit" class="btn btn-success btn-block">Finalizar e Gerar Certificado</button>
</form>
</div>
</div>
</div>
<script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
<script>const OS_ID=<?= $os['id']?>;</script>
<script src="<?= BASE_URL ?>/assets/js/provider-os-show.js"></script>
</body>
</html>
