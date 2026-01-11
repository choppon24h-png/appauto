/**
 * ============================================================================
 * APP AUTO - JavaScript de Autenticação
 * ============================================================================
 */

(function() {
    'use strict';

    // Elementos do DOM
    const loginForm = document.getElementById('login-form');
    const loginBtn = document.getElementById('login-btn');
    const togglePasswordBtn = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const emailInput = document.getElementById('email');
    const messageContainer = document.getElementById('message-container');

    // ========================================================================
    // Toggle de Senha
    // ========================================================================
    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Mudar ícone
            const svg = togglePasswordBtn.querySelector('svg');
            if (type === 'text') {
                svg.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            } else {
                svg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            }
        });
    }

    // ========================================================================
    // Validação de Email
    // ========================================================================
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // ========================================================================
    // Mostrar Mensagem
    // ========================================================================
    function showMessage(message, type = 'info') {
        const alertClass = type === 'error' ? 'alert-error' : (type === 'success' ? 'alert-success' : 'alert-info');
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${alertClass}`;
        alertDiv.textContent = message;
        
        messageContainer.innerHTML = '';
        messageContainer.appendChild(alertDiv);

        // Auto-remover após 5 segundos
        setTimeout(() => {
            alertDiv.style.opacity = '0';
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 300);
        }, 5000);
    }

    // ========================================================================
    // Mostrar Erro de Campo
    // ========================================================================
    function showFieldError(fieldId, message) {
        const errorSpan = document.getElementById(`${fieldId}-error`);
        if (errorSpan) {
            errorSpan.textContent = message;
        }
    }

    // ========================================================================
    // Limpar Erros
    // ========================================================================
    function clearErrors() {
        const errorSpans = document.querySelectorAll('.error-message');
        errorSpans.forEach(span => {
            span.textContent = '';
        });
    }

    // ========================================================================
    // Validação do Formulário
    // ========================================================================
    function validateForm() {
        clearErrors();
        let isValid = true;

        const email = emailInput.value.trim();
        const password = passwordInput.value;

        // Validar email
        if (!email) {
            showFieldError('email', 'O email é obrigatório');
            isValid = false;
        } else if (!validateEmail(email)) {
            showFieldError('email', 'Digite um email válido');
            isValid = false;
        }

        // Validar senha
        if (!password) {
            showFieldError('password', 'A senha é obrigatória');
            isValid = false;
        } else if (password.length < 6) {
            showFieldError('password', 'A senha deve ter no mínimo 6 caracteres');
            isValid = false;
        }

        return isValid;
    }

    // ========================================================================
    // Mostrar Loading
    // ========================================================================
    function setLoading(isLoading) {
        const btnText = loginBtn.querySelector('.btn-text');
        const btnLoader = loginBtn.querySelector('.btn-loader');

        if (isLoading) {
            btnText.style.display = 'none';
            btnLoader.style.display = 'block';
            loginBtn.disabled = true;
        } else {
            btnText.style.display = 'block';
            btnLoader.style.display = 'none';
            loginBtn.disabled = false;
        }
    }

    // ========================================================================
    // Submit do Formulário
    // ========================================================================
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validar formulário
            if (!validateForm()) {
                return;
            }

            // Mostrar loading
            setLoading(true);
            clearErrors();

            // Coletar dados
            const formData = new FormData(loginForm);

            try {
                // Fazer requisição
                const response = await fetch(loginForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.sucesso) {
                    // Sucesso
                    showMessage(data.mensagem || 'Login realizado com sucesso!', 'success');
                    
                    // Redirecionar após 1 segundo
                    setTimeout(() => {
                        window.location.href = data.redirect || '/';
                    }, 1000);
                } else {
                    // Erro
                    setLoading(false);
                    
                    if (data.erros) {
                        // Erros de validação
                        Object.keys(data.erros).forEach(field => {
                            showFieldError(field, data.erros[field]);
                        });
                    } else {
                        // Erro geral
                        showMessage(data.mensagem || 'Erro ao fazer login', 'error');
                    }
                }
            } catch (error) {
                setLoading(false);
                console.error('Erro:', error);
                showMessage('Erro ao conectar com o servidor. Tente novamente.', 'error');
            }
        });
    }

    // ========================================================================
    // Limpar erros ao digitar
    // ========================================================================
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            showFieldError('email', '');
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            showFieldError('password', '');
        });
    }

    // ========================================================================
    // Enter para submeter
    // ========================================================================
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && (e.target === emailInput || e.target === passwordInput)) {
            loginForm.dispatchEvent(new Event('submit'));
        }
    });

    // ========================================================================
    // Verificar se já está logado
    // ========================================================================
    function checkIfLoggedIn() {
        // Se houver um parâmetro de sucesso na URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('logout') === 'success') {
            showMessage('Logout realizado com sucesso!', 'success');
        }
    }

    // Executar ao carregar
    checkIfLoggedIn();

})();
