<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carteira Digital - APP AUTO</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/wallet.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include APP_PATH . '/app/Views/cliente/_sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <!-- Header -->
        <header class="content-header">
            <div class="header-left">
                <button class="mobile-menu-btn" id="mobile-menu-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <h1 class="page-title">Carteira Digital</h1>
            </div>
            <div class="header-right">
                <button class="btn btn-primary" onclick="openUploadModal()">
                    Enviar Documento
                </button>
            </div>
        </header>

        <!-- Mensagens -->
        <div id="message-container"></div>

        <!-- Alertas de Vencimento -->
        <?php if (!empty($alerts)): ?>
        <section class="alerts-section">
            <div class="section-header">
                <h2 class="section-title">Alertas de Vencimento</h2>
            </div>
            <div class="alerts-list">
                <?php foreach ($alerts as $alert): ?>
                <div class="alert alert-<?= $alert['type'] ?>">
                    <div class="alert-icon"><?= $alert['icon'] ?></div>
                    <div class="alert-content">
                        <div class="alert-title"><?= htmlspecialchars($alert['title']) ?></div>
                        <div class="alert-message"><?= htmlspecialchars($alert['message']) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Documentos por Veículo -->
        <?php if (!empty($documentsByVehicle)): ?>
        <?php foreach ($documentsByVehicle as $data): ?>
        <section class="vehicle-documents-section">
            <div class="section-header">
                <h2 class="section-title">
                    <?= htmlspecialchars($data['vehicle']['marca'] . ' ' . $data['vehicle']['modelo']) ?>
                    <span class="vehicle-placa"><?= htmlspecialchars($data['vehicle']['placa']) ?></span>
                </h2>
            </div>

            <div class="documents-grid">
                <?php foreach ($data['documents'] as $doc): ?>
                <div class="document-card" data-document-id="<?= $doc['id'] ?>">
                    <div class="document-preview">
                        <?php if (strpos($doc['arquivo_tipo'], 'image') !== false): ?>
                            <img src="<?= BASE_URL . $doc['arquivo_path'] ?>" alt="<?= htmlspecialchars($doc['tipo_documento']) ?>">
                        <?php else: ?>
                            <div class="pdf-icon">📄</div>
                            <div class="pdf-label">PDF</div>
                        <?php endif; ?>
                    </div>

                    <div class="document-info">
                        <h3 class="document-title"><?= htmlspecialchars($doc['tipo_documento']) ?></h3>
                        
                        <?php if ($doc['data_vencimento']): ?>
                        <?php
                            $today = new DateTime();
                            $expiry = new DateTime($doc['data_vencimento']);
                            $diff = $today->diff($expiry);
                            $daysUntilExpiry = $diff->invert ? -$diff->days : $diff->days;
                            
                            if ($daysUntilExpiry < 0) {
                                $badgeClass = 'badge-danger';
                                $badgeText = 'Vencido';
                            } elseif ($daysUntilExpiry <= 30) {
                                $badgeClass = 'badge-warning';
                                $badgeText = 'Vence em ' . $daysUntilExpiry . ' dias';
                            } else {
                                $badgeClass = 'badge-success';
                                $badgeText = 'Válido';
                            }
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= $badgeText ?></span>
                        <div class="document-expiry">
                            Vencimento: <?= date('d/m/Y', strtotime($doc['data_vencimento'])) ?>
                        </div>
                        <?php endif; ?>

                        <div class="document-meta">
                            <span>📅 <?= date('d/m/Y', strtotime($doc['data_upload'])) ?></span>
                            <span>📦 <?= number_format($doc['arquivo_tamanho'] / 1024, 0) ?> KB</span>
                        </div>

                        <?php if ($doc['observacoes']): ?>
                        <div class="document-notes">
                            <?= htmlspecialchars($doc['observacoes']) ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="document-actions">
                        <button class="btn-action" onclick="viewDocument(<?= $doc['id'] ?>)" title="Visualizar">
                            👁️
                        </button>
                        <a href="<?= BASE_URL ?>/cliente/carteira/<?= $doc['id'] ?>/download" class="btn-action" title="Download">
                            ⬇️
                        </a>
                        <button class="btn-action btn-danger" onclick="deleteDocument(<?= $doc['id'] ?>, '<?= htmlspecialchars($doc['tipo_documento']) ?>')" title="Excluir">
                            🗑️
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endforeach; ?>
        <?php else: ?>
        <!-- Empty State -->
        <section class="empty-state">
            <div class="empty-icon">📄</div>
            <h3 class="empty-title">Nenhum documento cadastrado</h3>
            <p class="empty-message">Comece enviando documentos dos seus veículos para manter tudo organizado e receber alertas de vencimento.</p>
            <button class="btn btn-primary" onclick="openUploadModal()">Enviar Primeiro Documento</button>
        </section>
        <?php endif; ?>
    </main>

    <!-- Modal de Upload -->
    <div class="modal" id="upload-modal" style="display: none;">
        <div class="modal-overlay" onclick="closeUploadModal()"></div>
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h3 class="modal-title">Enviar Documento</h3>
                <button class="modal-close" onclick="closeUploadModal()">×</button>
            </div>
            <div class="modal-body">
                <form id="upload-form" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                    <div class="form-group">
                        <label for="veiculo_id">Veículo *</label>
                        <select id="veiculo_id" name="veiculo_id" class="form-control" required>
                            <option value="">Selecione um veículo</option>
                            <?php foreach ($vehicles as $vehicle): ?>
                            <option value="<?= $vehicle['id'] ?>">
                                <?= htmlspecialchars($vehicle['marca'] . ' ' . $vehicle['modelo'] . ' - ' . $vehicle['placa']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="error-message" id="veiculo_id-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="tipo_documento">Tipo de Documento *</label>
                        <select id="tipo_documento" name="tipo_documento" class="form-control" required>
                            <option value="">Selecione</option>
                            <option value="CRLV">CRLV (Licenciamento)</option>
                            <option value="CNH">CNH</option>
                            <option value="IPVA">IPVA</option>
                            <option value="Seguro">Seguro</option>
                            <option value="Multa">Multa</option>
                            <option value="Nota Fiscal">Nota Fiscal</option>
                            <option value="Garantia">Garantia</option>
                            <option value="Manual">Manual do Proprietário</option>
                            <option value="Outro">Outro</option>
                        </select>
                        <span class="error-message" id="tipo_documento-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="arquivo">Arquivo (JPG, PNG ou PDF - Máx 5MB) *</label>
                        <div class="file-upload-area" id="file-upload-area">
                            <input type="file" id="arquivo" name="arquivo" accept=".jpg,.jpeg,.png,.pdf" required style="display: none;">
                            <div class="file-upload-placeholder">
                                <div class="upload-icon">📁</div>
                                <div class="upload-text">Clique ou arraste o arquivo aqui</div>
                                <div class="upload-hint">JPG, PNG ou PDF até 5MB</div>
                            </div>
                            <div class="file-preview" id="file-preview" style="display: none;">
                                <div class="preview-icon">📄</div>
                                <div class="preview-name" id="preview-name"></div>
                                <div class="preview-size" id="preview-size"></div>
                                <button type="button" class="preview-remove" onclick="removeFile()">×</button>
                            </div>
                        </div>
                        <span class="error-message" id="arquivo-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="data_vencimento">Data de Vencimento</label>
                        <input type="date" id="data_vencimento" name="data_vencimento" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="observacoes">Observações</label>
                        <textarea id="observacoes" name="observacoes" class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeUploadModal()">Cancelar</button>
                <button class="btn btn-primary" id="upload-btn" onclick="submitUpload()">
                    <span class="btn-text">Enviar Documento</span>
                    <span class="btn-loading" style="display: none;">Enviando...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Visualização -->
    <div class="modal" id="view-modal" style="display: none;">
        <div class="modal-overlay" onclick="closeViewModal()"></div>
        <div class="modal-content modal-xl">
            <div class="modal-header">
                <h3 class="modal-title">Visualizar Documento</h3>
                <button class="modal-close" onclick="closeViewModal()">×</button>
            </div>
            <div class="modal-body" style="padding: 0;">
                <iframe id="document-viewer" style="width: 100%; height: 70vh; border: none;"></iframe>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal" id="delete-modal" style="display: none;">
        <div class="modal-overlay" onclick="closeDeleteModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirmar Exclusão</h3>
                <button class="modal-close" onclick="closeDeleteModal()">×</button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o documento <strong id="document-name"></strong>?</p>
                <p class="text-danger">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancelar</button>
                <button class="btn btn-danger" id="confirm-delete-btn">Excluir</button>
            </div>
        </div>
    </div>

    <script src="<?= BASE_URL ?>/assets/js/dashboard.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/wallet.js"></script>
</body>
</html>
