<?php

/**
 * ============================================================================
 * APP AUTO - Configurações da Aplicação
 * ============================================================================
 */

return [
    // Informações da aplicação
    'name' => getenv('APP_NAME') ?: 'APP AUTO',
    'env' => getenv('APP_ENV') ?: 'production',
    'debug' => getenv('APP_DEBUG') ?: false,
    'url' => getenv('APP_URL') ?: 'https://erp.appauto.com.br',

    // Segurança
    'key' => getenv('APP_KEY') ?: 'your-app-key-here',
    'cipher' => 'AES-256-CBC',

    // Sessão
    'session' => [
        'lifetime' => (int)getenv('SESSION_LIFETIME') ?: 3600,
        'cookie' => 'APPAUTO_SESSION',
        'path' => '/',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict',
    ],

    // Banco de dados
    'database' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: 3306,
        'name' => getenv('DB_NAME') ?: 'appauto',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
        'collation' => getenv('DB_COLLATION') ?: 'utf8mb4_unicode_ci',
    ],

    // Upload
    'upload' => [
        'max_size' => (int)getenv('MAX_UPLOAD_SIZE') ?: 5242880, // 5MB
        'allowed_extensions' => explode(',', getenv('ALLOWED_EXTENSIONS') ?: 'jpg,jpeg,png,pdf,doc,docx'),
        'directory' => '/uploads',
    ],

    // Tokens
    'token' => [
        'length' => (int)getenv('TOKEN_LENGTH') ?: 6,
        'expiry' => (int)getenv('TOKEN_EXPIRY') ?: 600, // 10 minutos
    ],

    // Logging
    'logging' => [
        'channel' => getenv('LOG_CHANNEL') ?: 'single',
        'level' => getenv('LOG_LEVEL') ?: 'error',
        'path' => APP_PATH . '/storage/logs',
    ],

    // Cache
    'cache' => [
        'driver' => getenv('CACHE_DRIVER') ?: 'file',
        'lifetime' => (int)getenv('CACHE_LIFETIME') ?: 3600,
        'path' => APP_PATH . '/storage/cache',
    ],

    // Email
    'mail' => [
        'host' => getenv('MAIL_HOST') ?: 'smtp.mailtrap.io',
        'port' => getenv('MAIL_PORT') ?: 465,
        'username' => getenv('MAIL_USERNAME') ?: '',
        'password' => getenv('MAIL_PASSWORD') ?: '',
        'from' => getenv('MAIL_FROM') ?: 'noreply@appauto.com.br',
    ],

    // Papéis de usuário
    'roles' => [
        'admin' => 'Administrador',
        'cliente' => 'Cliente',
        'fornecedor' => 'Fornecedor',
    ],

    // Segmentos de fornecedor
    'segments' => [
        'oficina_mecanica' => 'Oficina Mecânica',
        'auto_pecas' => 'Auto Peças',
        'borracharia' => 'Borracharia',
        'eletricista' => 'Eletricista Automotivo',
        'funilaria' => 'Funilaria',
        'pintura' => 'Pintura',
        'vidracaria' => 'Vidraçaria',
        'lava_rapido' => 'Lava Rápido',
        'polimento' => 'Polimento',
        'blindagem' => 'Blindagem',
        'som_acessorios' => 'Som e Acessórios',
        'rastreamento' => 'Rastreamento',
        'seguro' => 'Seguro',
        'financiamento' => 'Financiamento',
        'locacao' => 'Locação',
    ],

    // Tipos de manutenção
    'maintenance_types' => [
        'oleo' => 'Troca de Óleo',
        'filtro_oleo' => 'Filtro de Óleo',
        'filtro_ar' => 'Filtro de Ar',
        'filtro_combustivel' => 'Filtro de Combustível',
        'pneu' => 'Pneu',
        'freio' => 'Freios',
        'bateria' => 'Bateria',
        'vela' => 'Vela',
        'correia' => 'Correia',
        'amortecedor' => 'Amortecedor',
        'revisao' => 'Revisão',
        'outro' => 'Outro',
    ],

    // Combustíveis
    'fuels' => [
        'gasolina' => 'Gasolina',
        'diesel' => 'Diesel',
        'etanol' => 'Etanol',
        'hibrido' => 'Híbrido',
        'eletrico' => 'Elétrico',
        'gnv' => 'GNV',
    ],

    // Paginação
    'pagination' => [
        'per_page' => 15,
    ],
];
