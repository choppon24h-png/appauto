<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="APP AUTO" class="sidebar-logo">
        <h2 class="sidebar-title">APP AUTO</h2>
    </div>
    <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>/fornecedor/dashboard" class="nav-item active">Dashboard</a>
        <a href="<?= BASE_URL ?>/fornecedor/clientes" class="nav-item">Clientes</a>
        <a href="<?= BASE_URL ?>/fornecedor/veiculos" class="nav-item">Veículos</a>
        <a href="<?= BASE_URL ?>/fornecedor/os" class="nav-item">Ordens de Serviço</a>
        <a href="<?= BASE_URL ?>/fornecedor/perfil" class="nav-item">Perfil</a>
        <a href="<?= BASE_URL ?>/logout" class="nav-item logout">Sair</a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar"><?= strtoupper(substr($user['nome'], 0, 1)) ?></div>
            <div class="user-details">
                <div class="user-name"><?= htmlspecialchars($user['nome']) ?></div>
                <div class="user-role">Fornecedor</div>
            </div>
        </div>
    </div>
</aside>
