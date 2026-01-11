<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="APP AUTO" class="sidebar-logo">
        <button class="sidebar-toggle" id="sidebar-toggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>/cliente/dashboard" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/cliente/dashboard') !== false ? 'active' : '' ?>">
            <span class="nav-text">Dashboard</span>
        </a>
        <a href="<?= BASE_URL ?>/cliente/veiculos" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/cliente/veiculos') !== false ? 'active' : '' ?>">
            <span class="nav-text">Meus Veículos</span>
        </a>
        <a href="<?= BASE_URL ?>/cliente/carteira" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/cliente/carteira') !== false ? 'active' : '' ?>">
            <span class="nav-text">Carteira Digital</span>
        </a>
        <a href="<?= BASE_URL ?>/cliente/manutencao" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/cliente/manutencao') !== false ? 'active' : '' ?>">
            <span class="nav-text">Manutenção</span>
        </a>
        <a href="<?= BASE_URL ?>/cliente/autenticacao" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/cliente/autenticacao') !== false ? 'active' : '' ?>">
            <span class="nav-text">Fornecedores</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar"><?= strtoupper(substr($user['nome'], 0, 2)) ?></div>
            <div class="user-details">
                <div class="user-name"><?= htmlspecialchars($user['nome']) ?></div>
                <div class="user-email"><?= htmlspecialchars($user['email']) ?></div>
            </div>
        </div>
        <a href="<?= BASE_URL ?>/perfil" class="nav-item">
            <span class="nav-text">Perfil</span>
        </a>
        <form action="<?= BASE_URL ?>/logout" method="POST">
            <button type="submit" class="nav-item logout-btn">
                <span class="nav-text">Sair</span>
            </button>
        </form>
    </div>
</aside>
