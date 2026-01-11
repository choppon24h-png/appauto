<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Validar Certificado - APP AUTO</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/certificate.css">
</head>
<body>
<div class="container">
<div class="header">
<img src="<?= BASE_URL ?>/assets/img/logo.png" alt="APP AUTO" class="logo">
<h1>Validação de Certificado</h1>
</div>
<div class="search-section">
<form method="GET" action="/certificado/validar">
<input type="text" name="code" placeholder="Digite o código do certificado" value="<?= htmlspecialchars($code??'')?>" required>
<button type="submit">Validar</button>
</form>
</div>
<?php if($code):?>
<?php if($valid):?>
<div class="result valid">
<div class="status-icon">✓</div>
<h2>Certificado Válido</h2>
<div class="certificate-info">
<div class="info-card">
<h3>Código do Certificado</h3>
<p class="code"><?= htmlspecialchars($os['certificado_codigo'])?></p>
</div>
<div class="info-grid">
<div class="info-item">
<span class="label">O.S:</span>
<span class="value">#<?= str_pad($os['id'],6,'0',STR_PAD_LEFT)?></span>
</div>
<div class="info-item">
<span class="label">Veículo:</span>
<span class="value"><?= htmlspecialchars($vehicle['marca'].' '.$vehicle['modelo'].' - '.$vehicle['placa'])?></span>
</div>
<div class="info-item">
<span class="label">Cliente:</span>
<span class="value"><?= htmlspecialchars($client['nome'])?></span>
</div>
<div class="info-item">
<span class="label">Fornecedor:</span>
<span class="value"><?= htmlspecialchars($provider['nome'])?></span>
</div>
<div class="info-item">
<span class="label">Data Conclusão:</span>
<span class="value"><?= date('d/m/Y H:i',strtotime($os['data_conclusao']))?></span>
</div>
<div class="info-item">
<span class="label">Valor Total:</span>
<span class="value">R$ <?= number_format($os['valor_total'],2,',','.')?></span>
</div>
</div>
<a href="/certificado/<?= $os['certificado_codigo']?>/download" class="btn-download">Baixar Certificado PDF</a>
</div>
</div>
<?php else:?>
<div class="result invalid">
<div class="status-icon">✗</div>
<h2>Certificado Inválido</h2>
<p>O código informado não corresponde a nenhum certificado válido.</p>
</div>
<?php endif;?>
<?php endif;?>
<div class="footer">
<p>&copy; <?= date('Y')?> APP AUTO - Todos os direitos reservados</p>
</div>
</div>
</body>
</html>
