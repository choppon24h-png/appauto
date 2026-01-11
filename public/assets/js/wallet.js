/**
 * ============================================================================
 * APP AUTO - JavaScript da Carteira Digital
 * ============================================================================
 */

(function() {
    'use strict';

    // ========================================================================
    // Upload Modal
    // ========================================================================
    
    window.openUploadModal = function() {
        const modal = document.getElementById('upload-modal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeUploadModal = function() {
        const modal = document.getElementById('upload-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            
            // Resetar formulário
            const form = document.getElementById('upload-form');
            if (form) {
                form.reset();
                removeFile();
                clearErrors();
            }
        }
    };

    // ========================================================================
    // File Upload
    // ========================================================================
    
    const fileInput = document.getElementById('arquivo');
    const fileUploadArea = document.getElementById('file-upload-area');
    const filePlaceholder = fileUploadArea?.querySelector('.file-upload-placeholder');
    const filePreview = document.getElementById('file-preview');

    // Click para selecionar arquivo
    if (fileUploadArea) {
        fileUploadArea.addEventListener('click', function(e) {
            if (!e.target.classList.contains('preview-remove')) {
                fileInput.click();
            }
        });
    }

    // Drag and Drop
    if (fileUploadArea) {
        fileUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', function() {
            this.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect(files[0]);
            }
        });
    }

    // Mudança de arquivo
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                handleFileSelect(this.files[0]);
            }
        });
    }

    function handleFileSelect(file) {
        // Validar tipo
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        if (!allowedTypes.includes(file.type)) {
            showError('arquivo', 'Tipo de arquivo não permitido. Use JPG, PNG ou PDF');
            removeFile();
            return;
        }

        // Validar tamanho (5MB)
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            showError('arquivo', 'Arquivo muito grande. Tamanho máximo: 5MB');
            removeFile();
            return;
        }

        // Mostrar preview
        if (filePlaceholder) filePlaceholder.style.display = 'none';
        if (filePreview) {
            filePreview.style.display = 'flex';
            
            const previewName = document.getElementById('preview-name');
            const previewSize = document.getElementById('preview-size');
            
            if (previewName) previewName.textContent = file.name;
            if (previewSize) previewSize.textContent = formatFileSize(file.size);
        }
    }

    window.removeFile = function() {
        if (fileInput) fileInput.value = '';
        if (filePlaceholder) filePlaceholder.style.display = 'block';
        if (filePreview) filePreview.style.display = 'none';
    };

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    // ========================================================================
    // Submit Upload
    // ========================================================================
    
    window.submitUpload = async function() {
        // Limpar erros
        clearErrors();

        // Validar campos
        const veiculoId = document.getElementById('veiculo_id').value;
        const tipoDocumento = document.getElementById('tipo_documento').value;
        const arquivo = fileInput.files[0];

        let hasError = false;

        if (!veiculoId) {
            showError('veiculo_id', 'Selecione um veículo');
            hasError = true;
        }

        if (!tipoDocumento) {
            showError('tipo_documento', 'Selecione o tipo de documento');
            hasError = true;
        }

        if (!arquivo) {
            showError('arquivo', 'Selecione um arquivo');
            hasError = true;
        }

        if (hasError) return;

        // Desabilitar botão
        const uploadBtn = document.getElementById('upload-btn');
        uploadBtn.classList.add('loading');
        uploadBtn.disabled = true;

        // Preparar FormData
        const formData = new FormData(document.getElementById('upload-form'));

        try {
            const response = await fetch('/cliente/carteira/upload', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const result = await response.json();

            if (result.sucesso) {
                closeUploadModal();
                showMessage('success', result.mensagem);
                
                // Recarregar página após 1 segundo
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                if (result.erros) {
                    for (const [field, message] of Object.entries(result.erros)) {
                        showError(field, message);
                    }
                } else {
                    showMessage('error', result.mensagem || 'Erro ao enviar documento');
                }

                // Reabilitar botão
                uploadBtn.classList.remove('loading');
                uploadBtn.disabled = false;
            }
        } catch (error) {
            console.error('Erro:', error);
            showMessage('error', 'Erro ao processar requisição');
            
            // Reabilitar botão
            uploadBtn.classList.remove('loading');
            uploadBtn.disabled = false;
        }
    };

    // ========================================================================
    // View Document
    // ========================================================================
    
    window.viewDocument = function(id) {
        const modal = document.getElementById('view-modal');
        const viewer = document.getElementById('document-viewer');
        
        if (modal && viewer) {
            viewer.src = `/cliente/carteira/${id}/view`;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeViewModal = function() {
        const modal = document.getElementById('view-modal');
        const viewer = document.getElementById('document-viewer');
        
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            
            if (viewer) {
                viewer.src = '';
            }
        }
    };

    // ========================================================================
    // Delete Document
    // ========================================================================
    
    let documentToDelete = null;

    window.deleteDocument = function(id, name) {
        documentToDelete = id;
        
        const modal = document.getElementById('delete-modal');
        const documentName = document.getElementById('document-name');
        
        if (modal && documentName) {
            documentName.textContent = name;
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
        documentToDelete = null;
    };

    // Confirmar exclusão
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', async function() {
            if (!documentToDelete) return;

            // Desabilitar botão
            confirmDeleteBtn.disabled = true;
            confirmDeleteBtn.textContent = 'Excluindo...';

            try {
                // Obter CSRF token
                const csrfToken = document.querySelector('input[name="csrf_token"]')?.value || '';

                // Enviar requisição
                const response = await fetch(`/cliente/carteira/${documentToDelete}`, {
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

                    // Remover card do documento
                    const documentCard = document.querySelector(`[data-document-id="${documentToDelete}"]`);
                    if (documentCard) {
                        documentCard.style.opacity = '0';
                        documentCard.style.transform = 'scale(0.9)';
                        setTimeout(() => {
                            documentCard.remove();

                            // Verificar se não há mais documentos
                            const remainingDocuments = document.querySelectorAll('.document-card');
                            if (remainingDocuments.length === 0) {
                                window.location.reload();
                            }
                        }, 300);
                    }

                    showMessage('success', result.mensagem);
                } else {
                    showMessage('error', result.mensagem || 'Erro ao excluir documento');
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
    // Fechar modais com ESC
    // ========================================================================
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeUploadModal();
            closeViewModal();
            closeDeleteModal();
        }
    });

})();
