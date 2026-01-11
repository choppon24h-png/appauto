<?php

/**
 * ============================================================================
 * APP AUTO - Base Controller
 * ============================================================================
 * 
 * Classe base para todos os controladores
 * 
 * @author APP AUTO Team
 * @version 1.0.0
 */

namespace Core;

class Controller
{
    /**
     * Renderizar view
     * 
     * @param string $view Nome da view
     * @param array $data Dados para a view
     * @return void
     */
    protected function view($view, $data = [])
    {
        $viewPath = APP_PATH . "/app/Views/{$view}.php";

        if (!file_exists($viewPath)) {
            throw new \Exception("View não encontrada: {$view}");
        }

        extract($data);
        require $viewPath;
    }

    /**
     * Retornar JSON
     * 
     * @param array $data Dados
     * @param int $statusCode Código HTTP
     * @return void
     */
    protected function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Redirecionar
     * 
     * @param string $url URL
     * @return void
     */
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Verificar autenticação
     * 
     * @return bool
     */
    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Obter usuário autenticado
     * 
     * @return array|null
     */
    protected function getAuthUser()
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Verificar autorização
     * 
     * @param string $role Papel
     * @return bool
     */
    protected function authorize($role)
    {
        if (!$this->isAuthenticated()) {
            return false;
        }

        $user = $this->getAuthUser();
        return $user['role'] === $role;
    }

    /**
     * Redirecionar se não autenticado
     * 
     * @return void
     */
    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }
    }

    /**
     * Redirecionar se não autorizado
     * 
     * @param string $role Papel
     * @return void
     */
    protected function requireRole($role)
    {
        if (!$this->authorize($role)) {
            http_response_code(403);
            echo "Acesso negado";
            exit;
        }
    }

    /**
     * Validar CSRF token
     * 
     * @return bool
     */
    protected function validateCsrf()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return true;
        }

        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

        if (!$token || $token !== $_SESSION['csrf_token']) {
            return false;
        }

        return true;
    }

    /**
     * Gerar CSRF token
     * 
     * @return string
     */
    protected function generateCsrfToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Validar entrada
     * 
     * @param array $data Dados
     * @param array $rules Regras
     * @return array Erros
     */
    protected function validate($data, $rules)
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            $ruleList = explode('|', $rule);

            foreach ($ruleList as $r) {
                if ($r === 'required' && empty($value)) {
                    $errors[$field] = "Campo obrigatório";
                } elseif (strpos($r, 'min:') === 0) {
                    $min = (int)substr($r, 4);
                    if (strlen($value) < $min) {
                        $errors[$field] = "Mínimo {$min} caracteres";
                    }
                } elseif (strpos($r, 'max:') === 0) {
                    $max = (int)substr($r, 4);
                    if (strlen($value) > $max) {
                        $errors[$field] = "Máximo {$max} caracteres";
                    }
                } elseif ($r === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "Email inválido";
                }
            }
        }

        return $errors;
    }

    /**
     * Sanitizar entrada
     * 
     * @param string $value Valor
     * @return string
     */
    protected function sanitize($value)
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Flash message
     * 
     * @param string $key Chave
     * @param string $message Mensagem
     * @param string $type Tipo
     * @return void
     */
    protected function flash($key, $message, $type = 'info')
    {
        $_SESSION['flash'][$key] = [
            'message' => $message,
            'type' => $type
        ];
    }

    /**
     * Obter flash message
     * 
     * @param string $key Chave
     * @return array|null
     */
    protected function getFlash($key)
    {
        $flash = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $flash;
    }
}
