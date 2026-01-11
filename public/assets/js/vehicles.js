/**
 * ============================================================================
 * APP AUTO - JavaScript de Veículos
 * ============================================================================
 */

(function() {
    'use strict';

    // ========================================================================
    // Máscaras de Entrada
    // ========================================================================
    
    // Máscara de Placa (ABC1234 ou ABC1D23)
    const placaInput = document.getElementById('placa');
    if (placaInput) {
        placaInput.addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            e.target.value = value;
        });
    }

    // Máscara de Ano (apenas números, 4 dígitos)
    const anoInput = document.getElementById('ano');
    if (anoInput) {
        anoInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '').substring(0, 4);
            e.target.value = value;
        });
    }

    // Máscara de KM (apenas números com separador de milhares)
    const kmInput = document.getElementById('km_atual');
    if (kmInput) {
        kmInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });

        kmInput.addEventListener('blur', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                e.target.value = parseInt(value).toLocaleString('pt-BR');
            }
        });

        kmInput.addEventListener('focus', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });
    }

    // Máscara de RENAVAM (apenas números, 11 dígitos)
    const renavamInput = document.getElementById('renavam');
    if (renavamInput) {
        renavamInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '').substring(0, 11);
            e.target.value = value;
        });
    }

    // Máscara de Chassi (apenas letras e números, 17 caracteres)
    const chassiInput = document.getElementById('chassi');
    if (chassiInput) {
        chassiInput.addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 17);
            e.target.value = value;
        });
    }

    // ========================================================================
    // Validação de Formulário
    // ========================================================================
    
    const vehicleForm = document.getElementById('vehicle-form');
    if (vehicleForm) {
        vehicleForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Limpar erros anteriores
            clearErrors();

            // Validar campos
            const marca = document.getElementById('marca').value.trim();
            const modelo = document.getElementById('modelo').value.trim();
            const ano = document.getElementById('ano').value.trim();
            const placa = document.getElementById('placa').value.trim();

            let hasError = false;

            if (!marca || marca.length < 2) {
                showError('marca', 'Marca deve ter pelo menos 2 caracteres');
                hasError = true;
            }

            if (!modelo || modelo.length < 2) {
                showError('modelo', 'Modelo deve ter pelo menos 2 caracteres');
                hasError = true;
            }

            if (!ano || ano.length !== 4 || parseInt(ano) < 1900 || parseInt(ano) > new Date().getFullYear() + 1) {
                showError('ano', 'Ano inválido');
                hasError = true;
            }

            if (!placa || placa.length < 7) {
                showError('placa', 'Placa inválida');
                hasError = true;
            }

            if (hasError) {
                return;
            }

            // Desabilitar botão e mostrar loading
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            // Preparar dados
            const formData = new FormData(vehicleForm);
            
            // Limpar KM (remover formatação)
            const kmValue = document.getElementById('km_atual').value.replace(/\D/g, '');
            formData.set('km_atual', kmValue);

            try {
                // Determinar método e URL
                const method = vehicleForm.dataset.method || 'POST';
                const url = vehicleForm.action;

                // Enviar requisição
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.sucesso) {
                    showMessage('success', result.mensagem);
                    
                    // Redirecionar após 1 segundo
                    setTimeout(() => {
                        window.location.href = result.redirect || '/cliente/veiculos';
                    }, 1000);
                } else {
                    if (result.erros) {
                        // Mostrar erros de validação
                        for (const [field, message] of Object.entries(result.erros)) {
                            showError(field, message);
                        }
                    } else {
                        showMessage('error', result.mensagem || 'Erro ao salvar veículo');
                    }

                    // Reabilitar botão
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }
            } catch (error) {
                console.error('Erro:', error);
                showMessage('error', 'Erro ao processar requisição');
                
                // Reabilitar botão
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            }
        });
    }

    // ========================================================================
    // Funções de Erro
    // ========================================================================
    
    function showError(field, message) {
        const input = document.getElementById(field);
        const errorElement = document.getElementById(field + '-error');

        if (input) {
            input.classList.add('error');
        }

        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }
    }

    function clearErrors() {
        const errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(el => {
            el.textContent = '';
            el.classList.remove('show');
        });

        const errorInputs = document.querySelectorAll('.form-control.error');
        errorInputs.forEach(el => {
            el.classList.remove('error');
        });
    }

    // Limpar erro ao digitar
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('error');
            const errorElement = document.getElementById(this.id + '-error');
            if (errorElement) {
                errorElement.classList.remove('show');
            }
        });
    });

    // ========================================================================
    // Mensagens
    // ========================================================================
    
    function showMessage(type, text) {
        const container = document.getElementById('message-container');
        if (!container) return;

        const message = document.createElement('div');
        message.className = `message message-${type}`;
        message.innerHTML = `
            <span class="message-icon">${type === 'success' ? '✓' : '✗'}</span>
            <span class="message-text">${text}</span>
            <button class="message-close" onclick="this.parentElement.remove()">×</button>
        `;

        container.appendChild(message);

        // Remover após 5 segundos
        setTimeout(() => {
            message.remove();
        }, 5000);

        // Scroll para o topo
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Tornar função disponível globalmente
    window.showMessage = showMessage;

    // ========================================================================
    // Ações de Veículo
    // ========================================================================
    
    // Editar veículo
    window.editVehicle = function(id) {
        window.location.href = `/cliente/veiculos/${id}/editar`;
    };

    // Deletar veículo
    let vehicleToDelete = null;

    window.deleteVehicle = function(id, name) {
        vehicleToDelete = id;
        
        const modal = document.getElementById('delete-modal');
        const vehicleName = document.getElementById('vehicle-name');
        
        if (modal && vehicleName) {
            vehicleName.textContent = name;
            modal.style.display = 'flex';
        }
    };

    window.closeDeleteModal = function() {
        const modal = document.getElementById('delete-modal');
        if (modal) {
            modal.style.display = 'none';
        }
        vehicleToDelete = null;
    };

    // Confirmar exclusão
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', async function() {
            if (!vehicleToDelete) return;

            // Desabilitar botão
            confirmDeleteBtn.disabled = true;
            confirmDeleteBtn.textContent = 'Excluindo...';

            try {
                // Obter CSRF token
                const csrfToken = document.querySelector('input[name="csrf_token"]')?.value || '';

                // Enviar requisição
                const response = await fetch(`/cliente/veiculos/${vehicleToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': csrfToken
                    },
                    body: JSON.stringify({
                        csrf_token: csrfToken
                    })
                });

                const result = await response.json();

                if (result.sucesso) {
                    // Fechar modal
                    closeDeleteModal();

                    // Remover card do veículo
                    const vehicleCard = document.querySelector(`[data-vehicle-id="${vehicleToDelete}"]`);
                    if (vehicleCard) {
                        vehicleCard.style.opacity = '0';
                        vehicleCard.style.transform = 'scale(0.9)';
                        setTimeout(() => {
                            vehicleCard.remove();

                            // Verificar se não há mais veículos
                            const remainingVehicles = document.querySelectorAll('.vehicle-card');
                            if (remainingVehicles.length === 0) {
                                window.location.reload();
                            }
                        }, 300);
                    }

                    showMessage('success', result.mensagem);
                } else {
                    showMessage('error', result.mensagem || 'Erro ao excluir veículo');
                    closeDeleteModal();
                }
            } catch (error) {
                console.error('Erro:', error);
                showMessage('error', 'Erro ao processar requisição');
                closeDeleteModal();
            } finally {
                // Reabilitar botão
                confirmDeleteBtn.disabled = false;
                confirmDeleteBtn.textContent = 'Excluir';
            }
        });
    }

    // Fechar modal ao clicar fora
    const deleteModal = document.getElementById('delete-modal');
    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    }

    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });

})();
