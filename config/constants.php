<?php

/**
 * ============================================================================
 * APP AUTO - Constantes da Aplicação
 * ============================================================================
 */

// Caminhos
define('APP_PATH', dirname(dirname(__FILE__)));
define('PUBLIC_PATH', APP_PATH . '/public');
define('CONFIG_PATH', APP_PATH . '/config');
define('CORE_PATH', APP_PATH . '/core');
define('APP_FOLDER', APP_PATH . '/app');
define('STORAGE_PATH', APP_PATH . '/storage');
define('LOGS_PATH', STORAGE_PATH . '/logs');
define('CACHE_PATH', STORAGE_PATH . '/cache');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');

// URLs
define('BASE_URL', getenv('APP_URL') ?: 'https://erp.appauto.com.br');
define('ASSET_URL', BASE_URL . '/assets');

// Ambiente
define('IS_PRODUCTION', getenv('APP_ENV') === 'production');
define('IS_DEBUG', getenv('APP_DEBUG') === 'true');

// Segurança
define('CSRF_TOKEN_NAME', '_token');
define('SESSION_TIMEOUT', (int)getenv('SESSION_LIFETIME') ?: 3600);

// Upload
define('MAX_UPLOAD_SIZE', (int)getenv('MAX_UPLOAD_SIZE') ?: 5242880);
define('ALLOWED_EXTENSIONS', explode(',', getenv('ALLOWED_EXTENSIONS') ?: 'jpg,jpeg,png,pdf,doc,docx'));

// Tokens
define('TOKEN_LENGTH', (int)getenv('TOKEN_LENGTH') ?: 6);
define('TOKEN_EXPIRY', (int)getenv('TOKEN_EXPIRY') ?: 600);

// Paginação
define('ITEMS_PER_PAGE', 15);

// Status
define('STATUS_ACTIVE', 'ativo');
define('STATUS_INACTIVE', 'inativo');
define('STATUS_PENDING', 'pendente');
define('STATUS_APPROVED', 'aprovado');
define('STATUS_REJECTED', 'rejeitado');
define('STATUS_IN_EXECUTION', 'execucao');
define('STATUS_AWAITING_PICKUP', 'aguardando_retirada');
define('STATUS_COMPLETED', 'concluido');

// Papéis
define('ROLE_ADMIN', 'admin');
define('ROLE_CLIENT', 'cliente');
define('ROLE_PROVIDER', 'fornecedor');

// Mensagens
define('MSG_SUCCESS', 'Operação realizada com sucesso');
define('MSG_ERROR', 'Erro ao processar a operação');
define('MSG_UNAUTHORIZED', 'Você não tem permissão para acessar este recurso');
define('MSG_NOT_FOUND', 'Recurso não encontrado');
define('MSG_VALIDATION_ERROR', 'Erro na validação dos dados');

// Erros HTTP
define('HTTP_OK', 200);
define('HTTP_CREATED', 201);
define('HTTP_BAD_REQUEST', 400);
define('HTTP_UNAUTHORIZED', 401);
define('HTTP_FORBIDDEN', 403);
define('HTTP_NOT_FOUND', 404);
define('HTTP_CONFLICT', 409);
define('HTTP_INTERNAL_ERROR', 500);
