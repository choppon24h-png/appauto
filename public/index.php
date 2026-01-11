<?php

/**
 * ============================================================================
 * APP AUTO - Front Controller
 * ============================================================================
 * 
 * Ponto de entrada da aplicação
 * 
 * @author APP AUTO Team
 * @version 1.0.0
 */

// ============================================================================
// INICIALIZAÇÃO
// ============================================================================

// Definir nível de erro
error_reporting(E_ALL);
ini_set('display_errors', getenv('APP_DEBUG') === 'true' ? 1 : 0);
ini_set('log_errors', 1);

// Definir timezone
date_default_timezone_set('America/Sao_Paulo');

// ============================================================================
// CARREGAMENTO DE CONFIGURAÇÕES
// ============================================================================

// Carregar arquivo .env
$envFile = dirname(dirname(__FILE__)) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, '"\'');
            putenv("{$key}={$value}");
        }
    }
}

// Carregar constantes
require_once dirname(dirname(__FILE__)) . '/config/constants.php';

// ============================================================================
// AUTOLOAD
// ============================================================================

spl_autoload_register(function ($class) {
    // Remover namespace raiz
    $class = ltrim($class, '\\');

    // Mapear namespaces para diretórios
    $namespace_map = [
        'Core\\' => CORE_PATH . '/',
        'App\\' => APP_FOLDER . '/',
        'Config\\' => CONFIG_PATH . '/',
    ];

    foreach ($namespace_map as $namespace => $path) {
        if (strpos($class, $namespace) === 0) {
            $class_path = $path . str_replace('\\', '/', substr($class, strlen($namespace))) . '.php';

            if (file_exists($class_path)) {
                require_once $class_path;
                return;
            }
        }
    }
});

// ============================================================================
// INICIAR SESSÃO
// ============================================================================

$config = require CONFIG_PATH . '/app.php';
$session_config = $config['session'];

session_set_cookie_params([
    'lifetime' => $session_config['lifetime'],
    'path' => $session_config['path'],
    'domain' => $session_config['domain'],
    'secure' => $session_config['secure'],
    'httponly' => $session_config['httponly'],
    'samesite' => $session_config['samesite'],
]);

session_start();

// Verificar timeout de sessão
if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > $session_config['lifetime']) {
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
$_SESSION['last_activity'] = time();

// ============================================================================
// MIDDLEWARE
// ============================================================================

// Verificar HTTPS em produção
if (IS_PRODUCTION && empty($_SERVER['HTTPS'])) {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}

// Headers de segurança
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\'; style-src \'self\' \'unsafe-inline\'; img-src \'self\' data: https:; font-src \'self\' data:; connect-src \'self\';');

// ============================================================================
// CARREGAR ROTAS
// ============================================================================

require APP_FOLDER . '/Routes/web.php';
