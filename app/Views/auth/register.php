<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Criar Conta - APP AUTO</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/register.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box register-box">
            <!-- Logo -->
            <div class="auth-logo">
                <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="APP AUTO">
            </div>

            <!-- Título -->
            <h1 class="auth-title">Criar Conta</h1>
            <p class="auth-subtitle">Cadastre-se gratuitamente no APP AUTO</p>

            <!-- Mensagens de erro/sucesso -->
            <div id="message-container"></div>

            <!-- Seleção de Perfil -->
            <div class="role-selector">
                <button type="button" class="role-btn active" data-role="cliente">
                    <div class="role-icon">👤</div>
                    <div class="role-title">Sou Cliente</div>
                    <div class="role-desc">Gerenciar meus veículos</div>
                </button>
                <button type="button" class="role-btn" data-role="fornecedor">
                    <div class="role-icon">🏢</div>
                    <div class="role-title">Sou Fornecedor</div>
                    <div class="role-desc">Prestar serviços automotivos</div>
                </button>
            </div>

            <!-- Formulário de Registro -->
            <form id="register-form" class="auth-form" method="POST" action="<?= BASE_URL ?>/register" enctype="multipart/form-data">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <input type="hidden" name="role" id="role-input" value="cliente">

                <!-- Nome / Razão Social -->
                <div class="form-group">
                    <label for="nome" id="nome-label">Nome Completo</label>
                    <input 
                        type="text" 
                        id="nome" 
                        name="nome" 
                        class="form-control" 
                        placeholder="Digite seu nome completo"
                        required
                        autofocus
                    >
                    <span class="error-message" id="nome-error"></span>
                </div>

                <!-- CPF / CNPJ -->
                <div class="form-group">
                    <label for="cpf_cnpj" id="cpf-label">CPF</label>
                    <input 
                        type="text" 
                        id="cpf_cnpj" 
                        name="cpf_cnpj" 
                        class="form-control" 
                        placeholder="000.000.000-00"
                        maxlength="18"
                        required
                    >
                    <span class="error-message" id="cpf_cnpj-error"></span>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        placeholder="seu@email.com"
                        required
                    >
                    <span class="error-message" id="email-error"></span>
                </div>

                <!-- Telefone -->
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input 
                        type="tel" 
                        id="telefone" 
                        name="telefone" 
                        class="form-control" 
                        placeholder="(00) 00000-0000"
                        maxlength="15"
                    >
                    <span class="error-message" id="telefone-error"></span>
                </div>

                <!-- Campos Específicos de Fornecedor -->
                <div id="fornecedor-fields" style="display: none;">
                    <!-- Nome do Estabelecimento -->
                    <div class="form-group">
                        <label for="nome_estabelecimento">Nome do Estabelecimento</label>
                        <input 
                            type="text" 
                            id="nome_estabelecimento" 
                            name="nome_estabelecimento" 
                            class="form-control" 
                            placeholder="Nome da sua empresa"
                        >
                        <span class="error-message" id="nome_estabelecimento-error"></span>
                    </div>

                    <!-- Segmento -->
                    <div class="form-group">
                        <label for="segmento">Segmento de Atuação</label>
                        <select id="segmento" name="segmento" class="form-control">
                            <?php if (isset($segments)): ?>
                                <?php foreach ($segments as $key => $value): ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="oficina_mecanica">Oficina Mecânica</option>
                                <option value="auto_pecas">Auto Peças</option>
                                <option value="borracharia">Borracharia</option>
                                <option value="eletricista">Elétrica Automotiva</option>
                                <option value="funilaria">Funilaria e Pintura</option>
                                <option value="vidracaria">Vidraçaria</option>
                                <option value="lava_rapido">Lava Rápido</option>
                                <option value="polimento">Polimento e Estética</option>
                                <option value="blindagem">Blindagem</option>
                                <option value="som_acessorios">Som e Acessórios</option>
                                <option value="rastreamento">Rastreamento</option>
                                <option value="seguro">Seguro Automotivo</option>
                                <option value="financiamento">Financiamento</option>
                                <option value="locacao">Locação de Veículos</option>
                                <option value="concessionaria">Concessionária</option>
                                <option value="despachante">Despachante</option>
                                <option value="outro">Outro</option>
                            <?php endif; ?>
                        </select>
                        <span class="error-message" id="segmento-error"></span>
                    </div>

                    <!-- Upload de Logo -->
                    <div class="form-group">
                        <label for="logo">Logo da Empresa (opcional)</label>
                        <div class="file-upload-wrapper">
                            <input 
                                type="file" 
                                id="logo" 
                                name="logo" 
                                class="file-input"
                                accept="image/jpeg,image/png,image/jpg"
                            >
                            <label for="logo" class="file-label">
                                <span class="file-icon">📁</span>
                                <span class="file-text">Escolher arquivo</span>
                            </label>
                            <div class="file-preview" id="logo-preview" style="display: none;">
                                <img src="" alt="Preview" id="logo-preview-img">
                                <button type="button" class="remove-file" id="remove-logo">×</button>
                            </div>
                        </div>
                        <small class="form-text">Formatos aceitos: JPG, PNG. Tamanho máximo: 2MB</small>
                        <span class="error-message" id="logo-error"></span>
                    </div>

                    <!-- Endereço -->
                    <div class="form-group">
                        <label for="endereco">Endereço</label>
                        <input 
                            type="text" 
                            id="endereco" 
                            name="endereco" 
                            class="form-control" 
                            placeholder="Rua, número, bairro"
                        >
                        <span class="error-message" id="endereco-error"></span>
                    </div>

                    <!-- Cidade e Estado -->
                    <div class="form-row">
                        <div class="form-group form-col-8">
                            <label for="cidade">Cidade</label>
                            <input 
                                type="text" 
                                id="cidade" 
                                name="cidade" 
                                class="form-control" 
                                placeholder="Cidade"
                            >
                            <span class="error-message" id="cidade-error"></span>
                        </div>
                        <div class="form-group form-col-4">
                            <label for="estado">UF</label>
                            <select id="estado" name="estado" class="form-control">
                                <option value="">UF</option>
                                <option value="AC">AC</option>
                                <option value="AL">AL</option>
                                <option value="AP">AP</option>
                                <option value="AM">AM</option>
                                <option value="BA">BA</option>
                                <option value="CE">CE</option>
                                <option value="DF">DF</option>
                                <option value="ES">ES</option>
                                <option value="GO">GO</option>
                                <option value="MA">MA</option>
                                <option value="MT">MT</option>
                                <option value="MS">MS</option>
                                <option value="MG">MG</option>
                                <option value="PA">PA</option>
                                <option value="PB">PB</option>
                                <option value="PR">PR</option>
                                <option value="PE">PE</option>
                                <option value="PI">PI</option>
                                <option value="RJ">RJ</option>
                                <option value="RN">RN</option>
                                <option value="RS">RS</option>
                                <option value="RO">RO</option>
                                <option value="RR">RR</option>
                                <option value="SC">SC</option>
                                <option value="SP">SP</option>
                                <option value="SE">SE</option>
                                <option value="TO">TO</option>
                            </select>
                            <span class="error-message" id="estado-error"></span>
                        </div>
                    </div>

                    <!-- CEP -->
                    <div class="form-group">
                        <label for="cep">CEP</label>
                        <input 
                            type="text" 
                            id="cep" 
                            name="cep" 
                            class="form-control" 
                            placeholder="00000-000"
                            maxlength="9"
                        >
                        <span class="error-message" id="cep-error"></span>
                    </div>
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
                            placeholder="Mínimo 6 caracteres"
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

                <!-- Confirmar Senha -->
                <div class="form-group">
                    <label for="password_confirm">Confirmar Senha</label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="password_confirm" 
                            name="password_confirm" 
                            class="form-control" 
                            placeholder="Digite a senha novamente"
                            required
                        >
                        <button type="button" class="toggle-password" id="toggle-password-confirm">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                    <span class="error-message" id="password_confirm-error"></span>
                </div>

                <!-- Termos -->
                <div class="form-group form-check">
                    <input type="checkbox" id="terms" name="terms" class="form-check-input" required>
                    <label for="terms" class="form-check-label">
                        Aceito os <a href="#" class="link">Termos de Uso</a> e a <a href="#" class="link">Política de Privacidade</a>
                    </label>
                    <span class="error-message" id="terms-error"></span>
                </div>

                <!-- Botão de Registro -->
                <button type="submit" class="btn btn-primary btn-block" id="register-btn">
                    <span class="btn-text">Criar Conta</span>
                    <span class="btn-loader" style="display: none;">
                        <svg class="spinner" width="20" height="20" viewBox="0 0 50 50">
                            <circle cx="25" cy="25" r="20" fill="none" stroke="currentColor" stroke-width="5"></circle>
                        </svg>
                    </span>
                </button>
            </form>

            <!-- Links -->
            <div class="auth-links">
                <span>Já tem uma conta?</span>
                <a href="<?= BASE_URL ?>/login" class="link">Fazer login</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="auth-footer">
            <p>&copy; <?= date('Y') ?> APP AUTO. Todos os direitos reservados.</p>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/assets/js/auth.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/register.js"></script>
</body>
</html>
