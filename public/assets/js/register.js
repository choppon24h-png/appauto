/**
 * ============================================================================
 * APP AUTO - JavaScript de Registro
 * ============================================================================
 */

(function() {
    'use strict';

    // Elementos do DOM
    const registerForm = document.getElementById('register-form');
    const registerBtn = document.getElementById('register-btn');
    const roleButtons = document.querySelectorAll('.role-btn');
    const roleInput = document.getElementById('role-input');
    const fornecedorFields = document.getElementById('fornecedor-fields');
    const nomeLabel = document.getElementById('nome-label');
    const cpfLabel = document.getElementById('cpf-label');
    const cpfCnpjInput = document.getElementById('cpf_cnpj');
    const telefoneInput = document.getElementById('telefone');
    const cepInput = document.getElementById('cep');
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logo-preview');
    const logoPreviewImg = document.getElementById('logo-preview-img');
    const removeLogo = document.getElementById('remove-logo');
    const togglePasswordBtn = document.getElementById('toggle-password');
    const togglePasswordConfirmBtn = document.getElementById('toggle-password-confirm');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirm');
    const messageContainer = document.getElementById('message-container');

    // ========================================================================
    // Seleção de Perfil
    // ========================================================================
    roleButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remover active de todos
            roleButtons.forEach(b => b.classList.remove('active'));
            
            // Adicionar active no clicado
            this.classList.add('active');
            
            // Atualizar input hidden
            const role = this.dataset.role;
            roleInput.value = role;
            
            // Mostrar/ocultar campos de fornecedor
            if (role === 'fornecedor') {
                fornecedorFields.style.display = 'block';
                nomeLabel.textContent = 'Razão Social';
                cpfLabel.textContent = 'CNPJ';
                cpfCnpjInput.placeholder = '00.000.000/0000-00';
                cpfCnpjInput.maxLength = 18;
            } else {
                fornecedorFields.style.display = 'none';
                nomeLabel.textContent = 'Nome Completo';
                cpfLabel.textContent = 'CPF';
                cpfCnpjInput.placeholder = '000.000.000-00';
                cpfCnpjInput.maxLength = 14;
            }
        });
    });

    // ========================================================================
    // Máscaras de Entrada
    // ========================================================================
    
    // Máscara de CPF/CNPJ
    if (cpfCnpjInput) {
        cpfCnpjInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            const role = roleInput.value;
            
            if (role === 'fornecedor') {
                // CNPJ: 00.000.000/0000-00
                if (value.length <= 14) {
                    value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                    value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                }
            } else {
                // CPF: 000.000.000-00
                if (value.length <= 11) {
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                }
            }
            
            e.target.value = value;
        });
    }

    // Máscara de Telefone
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // (00) 00000-0000
            if (value.length <= 11) {
                value = value.replace(/^(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            
            e.target.value = value;
        });
    }

    // Máscara de CEP
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // 00000-000
            if (value.length <= 8) {
                value = value.replace(/^(\d{5})(\d)/, '$1-$2');
            }
            
            e.target.value = value;
        });
    }

    // ========================================================================
    // Upload de Logo
    // ========================================================================
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validar tamanho (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showFieldError('logo', 'Arquivo muito grande. Máximo 2MB');
                    e.target.value = '';
                    return;
                }
                
                // Validar tipo
                if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                    showFieldError('logo', 'Formato inválido. Use JPG ou PNG');
                    e.target.value = '';
                    return;
                }
                
                // Mostrar preview
                const reader = new FileReader();
                reader.onload = function(event) {
                    logoPreviewImg.src = event.target.result;
                    logoPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
                
                // Limpar erro
                showFieldError('logo', '');
            }
        });
    }

    // Remover logo
    if (removeLogo) {
        removeLogo.addEventListener('click', function() {
            logoInput.value = '';
            logoPreview.style.display = 'none';
            logoPreviewImg.src = '';
        });
    }

    // ========================================================================
    // Toggle de Senha
    // ========================================================================
    function setupPasswordToggle(btn, input) {
        if (btn && input) {
            btn.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                const svg = btn.querySelector('svg');
                if (type === 'text') {
                    svg.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
                } else {
                    svg.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
                }
            });
        }
    }

    setupPasswordToggle(togglePasswordBtn, passwordInput);
    setupPasswordToggle(togglePasswordConfirmBtn, passwordConfirmInput);

    // ========================================================================
    // Validação
    // ========================================================================
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validateCPF(cpf) {
        cpf = cpf.replace(/\D/g, '');
        
        if (cpf.length !== 11) return false;
        if (/^(\d)\1+$/.test(cpf)) return false;
        
        let sum = 0;
        for (let i = 0; i < 9; i++) {
            sum += parseInt(cpf.charAt(i)) * (10 - i);
        }
        let digit = 11 - (sum % 11);
        if (digit >= 10) digit = 0;
        if (digit !== parseInt(cpf.charAt(9))) return false;
        
        sum = 0;
        for (let i = 0; i < 10; i++) {
            sum += parseInt(cpf.charAt(i)) * (11 - i);
        }
        digit = 11 - (sum % 11);
        if (digit >= 10) digit = 0;
        if (digit !== parseInt(cpf.charAt(10))) return false;
        
        return true;
    }

    function validateCNPJ(cnpj) {
        cnpj = cnpj.replace(/\D/g, '');
        
        if (cnpj.length !== 14) return false;
        if (/^(\d)\1+$/.test(cnpj)) return false;
        
        let size = cnpj.length - 2;
        let numbers = cnpj.substring(0, size);
        let digits = cnpj.substring(size);
        let sum = 0;
        let pos = size - 7;
        
        for (let i = size; i >= 1; i--) {
            sum += numbers.charAt(size - i) * pos--;
            if (pos < 2) pos = 9;
        }
        
        let result = sum % 11 < 2 ? 0 : 11 - (sum % 11);
        if (result !== parseInt(digits.charAt(0))) return false;
        
        size = size + 1;
        numbers = cnpj.substring(0, size);
        sum = 0;
        pos = size - 7;
        
        for (let i = size; i >= 1; i--) {
            sum += numbers.charAt(size - i) * pos--;
            if (pos < 2) pos = 9;
        }
        
        result = sum % 11 < 2 ? 0 : 11 - (sum % 11);
        if (result !== parseInt(digits.charAt(1))) return false;
        
        return true;
    }

    function showMessage(message, type = 'info') {
        const alertClass = type === 'error' ? 'alert-error' : (type === 'success' ? 'alert-success' : 'alert-info');
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${alertClass}`;
        alertDiv.textContent = message;
        
        messageContainer.innerHTML = '';
        messageContainer.appendChild(alertDiv);

        setTimeout(() => {
            alertDiv.style.opacity = '0';
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 300);
        }, 5000);
    }

    function showFieldError(fieldId, message) {
        const errorSpan = document.getElementById(`${fieldId}-error`);
        if (errorSpan) {
            errorSpan.textContent = message;
        }
    }

    function clearErrors() {
        const errorSpans = document.querySelectorAll('.error-message');
        errorSpans.forEach(span => {
            span.textContent = '';
        });
    }

    function validateForm() {
        clearErrors();
        let isValid = true;

        const nome = document.getElementById('nome').value.trim();
        const cpf_cnpj = cpfCnpjInput.value.trim();
        const email = document.getElementById('email').value.trim();
        const password = passwordInput.value;
        const password_confirm = passwordConfirmInput.value;
        const terms = document.getElementById('terms').checked;
        const role = roleInput.value;

        // Validar nome
        if (!nome) {
            showFieldError('nome', 'Nome é obrigatório');
            isValid = false;
        } else if (nome.length < 3) {
            showFieldError('nome', 'Nome deve ter no mínimo 3 caracteres');
            isValid = false;
        }

        // Validar CPF/CNPJ
        if (!cpf_cnpj) {
            showFieldError('cpf_cnpj', role === 'fornecedor' ? 'CNPJ é obrigatório' : 'CPF é obrigatório');
            isValid = false;
        } else {
            const cleanDoc = cpf_cnpj.replace(/\D/g, '');
            if (role === 'fornecedor') {
                if (!validateCNPJ(cleanDoc)) {
                    showFieldError('cpf_cnpj', 'CNPJ inválido');
                    isValid = false;
                }
            } else {
                if (!validateCPF(cleanDoc)) {
                    showFieldError('cpf_cnpj', 'CPF inválido');
                    isValid = false;
                }
            }
        }

        // Validar email
        if (!email) {
            showFieldError('email', 'Email é obrigatório');
            isValid = false;
        } else if (!validateEmail(email)) {
            showFieldError('email', 'Email inválido');
            isValid = false;
        }

        // Validar senha
        if (!password) {
            showFieldError('password', 'Senha é obrigatória');
            isValid = false;
        } else if (password.length < 6) {
            showFieldError('password', 'Senha deve ter no mínimo 6 caracteres');
            isValid = false;
        }

        // Validar confirmação de senha
        if (!password_confirm) {
            showFieldError('password_confirm', 'Confirmação de senha é obrigatória');
            isValid = false;
        } else if (password !== password_confirm) {
            showFieldError('password_confirm', 'Senhas não conferem');
            isValid = false;
        }

        // Validar termos
        if (!terms) {
            showFieldError('terms', 'Você deve aceitar os termos');
            isValid = false;
        }

        return isValid;
    }

    function setLoading(isLoading) {
        const btnText = registerBtn.querySelector('.btn-text');
        const btnLoader = registerBtn.querySelector('.btn-loader');

        if (isLoading) {
            btnText.style.display = 'none';
            btnLoader.style.display = 'block';
            registerBtn.disabled = true;
        } else {
            btnText.style.display = 'block';
            btnLoader.style.display = 'none';
            registerBtn.disabled = false;
        }
    }

    // ========================================================================
    // Submit do Formulário
    // ========================================================================
    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!validateForm()) {
                return;
            }

            setLoading(true);
            clearErrors();

            const formData = new FormData(registerForm);

            try {
                const response = await fetch(registerForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.sucesso) {
                    showMessage(data.mensagem || 'Conta criada com sucesso!', 'success');
                    
                    setTimeout(() => {
                        window.location.href = data.redirect || '/login';
                    }, 2000);
                } else {
                    setLoading(false);
                    
                    if (data.erros) {
                        Object.keys(data.erros).forEach(field => {
                            showFieldError(field, data.erros[field]);
                        });
                    } else {
                        showMessage(data.mensagem || 'Erro ao criar conta', 'error');
                    }
                }
            } catch (error) {
                setLoading(false);
                console.error('Erro:', error);
                showMessage('Erro ao conectar com o servidor. Tente novamente.', 'error');
            }
        });
    }

    // Limpar erros ao digitar
    const inputs = registerForm.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            showFieldError(this.id || this.name, '');
        });
    });

})();
