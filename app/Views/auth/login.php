<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - APP AUTO</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <!-- Logo -->
            <div class="auth-logo">
                <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="APP AUTO">
            </div>

            <!-- Título -->
            <h1 class="auth-title">Bem-vindo ao APP AUTO</h1>
            <p class="auth-subtitle">Faça login para acessar sua conta</p>

            <!-- Mensagens de erro/sucesso -->
            <div id="message-container"></div>

            <!-- Formulário de Login -->
            <form id="login-form" class="auth-form" method="POST" action="<?= BASE_URL ?>/login">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                <!-- Email -->
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        placeholder="seu@email.com"
                        value="admin@appauto.com.br"
                        required
                        autofocus
                    >
                    <span class="error-message" id="email-error"></span>
                </div>

                <!-- Senha -->
                <div class="form-group">
                    <label for="password">Senha</label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="Digite sua senha"
                            value="admin1234"
                            required
                        >
                        <button type="button" class="toggle-password" id="toggle-password">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                    <span class="error-message" id="password-error"></span>
                </div>

                <!-- Lembrar-me -->
                <div class="form-group form-check">
                    <input type="checkbox" id="remember" name="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">Lembrar-me</label>
                </div>

                <!-- Botão de Login -->
                <button type="submit" class="btn btn-primary btn-block" id="login-btn">
                    <span class="btn-text">Entrar</span>
                    <span class="btn-loader" style="display: none;">
                        <svg class="spinner" width="20" height="20" viewBox="0 0 50 50">
                            <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor" stroke-width="5"></circle>
                        </svg>
                    </span>
                </button>
            </form>

            <!-- Links -->
            <div class="auth-links">
                <a href="<?= BASE_URL ?>/forgot-password" class="link">Esqueceu sua senha?</a>
                <span class="separator">•</span>
                <a href="<?= BASE_URL ?>/register" class="link">Criar conta</a>
            </div>

            <!-- Informações de teste -->
            <div class="test-info">
                <p><strong>Credenciais de teste:</strong></p>
                <p>Email: admin@appauto.com.br</p>
                <p>Senha: admin1234</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            <p>&copy; <?= date('Y') ?> APP AUTO. Todos os direitos reservados.</p>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/assets/js/auth.js"></script>
</body>
</html>
