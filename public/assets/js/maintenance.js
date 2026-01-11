/**
 * ============================================================================
 * APP AUTO - JavaScript de Manutenção
 * ============================================================================
 */

(function() {
    'use strict';

    // ========================================================================
    // Máscaras de Entrada
    // ========================================================================
    
    const kmAtualInput = document.getElementById('km_atual');
    const proximaManutencaoKmInput = document.getElementById('proxima_manutencao_km');
    const custoInput = document.getElementById('custo');

    // Máscara de KM
    if (kmAtualInput) {
        kmAtualInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            this.value = value;
        });
    }

    if (proximaManutencaoKmInput) {
        proximaManutencaoKmInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            this.value = value;
        });
    }

    // Máscara de moeda
    if (custoInput) {
        custoInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            value = (parseInt(value) / 100).toFixed(2);
            this.value = value.replace('.', ',');
        });
    }

    // ========================================================================
    // Preencher KM Atual do Veículo
    // ========================================================================
    
    const veiculoSelect = document.getElementById('veiculo_id');
    if (veiculoSelect && kmAtualInput) {
        veiculoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const kmVeiculo = selectedOption.getAttribute('data-km');
            
            if (kmVeiculo && !kmAtualInput.value) {
                kmAtualInput.value = kmVeiculo;
            }
        });
    }

    // ========================================================================
    // Submit do Formulário
    // ========================================================================
    
    const maintenanceForm = document.getElementById('maintenance-form');
    if (maintenanceForm) {
        maintenanceForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Limpar erros
            clearErrors();

            // Validar campos
            const veiculoId = document.getElementById('veiculo_id')?.value;
            const tipoManutencao = document.getElementById('tipo_manutencao').value;
            const descricao = document.getElementById('descricao').value;
            const kmAtual = kmAtualInput.value;
            const proximaManutencaoKm = proximaManutencaoKmInput?.value || '';

            let hasError = false;

            if (veiculoId !== undefined && !veiculoId) {
                showError('veiculo_id', 'Selecione um veículo');
                hasError = true;
            }

            if (!tipoManutencao) {
                showError('tipo_manutencao', 'Selecione o tipo de manutenção');
                hasError = true;
            }

            if (!descricao || descricao.length < 5) {
                showError('descricao', 'Descrição deve ter pelo menos 5 caracteres');
                hasError = true;
            }

            if (!kmAtual || isNaN(kmAtual) || parseInt(kmAtual) < 0) {
                showError('km_atual', 'KM atual inválido');
                hasError = true;
            }

            if (proximaManutencaoKm && (isNaN(proximaManutencaoKm) || parseInt(proximaManutencaoKm) <= parseInt(kmAtual))) {
                showError('proxima_manutencao_km', 'Próxima manutenção deve ser maior que KM atual');
                hasError = true;
            }

            if (hasError) return;

            // Desabilitar botão
            const submitBtn = document.getElementById('submit-btn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';

            // Preparar FormData
            const formData = new FormData(this);

            // Determinar método e URL
            const method = this.getAttribute('data-method') || 'POST';
            const url = this.action;

            try {
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
                        window.location.href = result.redirect || '/cliente/manutencao';
                    }, 1000);
                } else {
                    if (result.erros) {
                        for (const [field, message] of Object.entries(result.erros)) {
                            showError(field, message);
                        }
                    } else {
                        showMessage('error', result.mensagem || 'Erro ao processar requisição');
                    }

                    // Reabilitar botão
                    submitBtn.disabled = false;
                    btnText.style.display = 'inline';
                    btnLoading.style.display = 'none';
                }
            } catch (error) {
                console.error('Erro:', error);
                showMessage('error', 'Erro ao processar requisição');
                
                // Reabilitar botão
                submitBtn.disabled = false;
                btnText.style.display = 'inline';
                btnLoading.style.display = 'none';
            }
        });
    }

    // ========================================================================
    // Excluir Manutenção
    // ========================================================================
    
    let maintenanceToDelete = null;

    window.deleteMaintenance = function(id, name) {
        maintenanceToDelete = id;
        
        const modal = document.getElementById('delete-modal');
        const maintenanceName = document.getElementById('maintenance-name');
        
        if (modal && maintenanceName) {
            maintenanceName.textContent = name;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeDeleteModal = function() {
        const modal = document.getElementById('delete-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
        maintenanceToDelete = null;
    };

    // Confirmar exclusão
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', async function() {
            if (!maintenanceToDelete) return;

            // Desabilitar botão
            confirmDeleteBtn.disabled = true;
            confirmDeleteBtn.textContent = 'Excluindo...';

            try {
                // Obter CSRF token
                const csrfToken = document.querySelector('input[name="csrf_token"]')?.value || '';

                // Enviar requisição
                const response = await fetch(`/cliente/manutencao/${maintenanceToDelete}`, {
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

                    // Remover card da manutenção
                    const maintenanceCard = document.querySelector(`[data-maintenance-id="${maintenanceToDelete}"]`);
                    if (maintenanceCard) {
                        maintenanceCard.style.opacity = '0';
                        maintenanceCard.style.transform = 'scale(0.9)';
                        setTimeout(() => {
                            maintenanceCard.remove();

                            // Verificar se não há mais manutenções
                            const remainingMaintenances = document.querySelectorAll('.maintenance-card');
                            if (remainingMaintenances.length === 0) {
                                window.location.reload();
                            }
                        }, 300);
                    }

                    showMessage('success', result.mensagem);
                } else {
                    showMessage('error', result.mensagem || 'Erro ao excluir manutenção');
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
    // Fechar modal com ESC
    // ========================================================================
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });

})();
