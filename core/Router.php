<?php

/**
 * ============================================================================
 * APP AUTO - Router
 * ============================================================================
 * 
 * Classe responsável por gerenciar rotas da aplicação
 * 
 * @author APP AUTO Team
 * @version 1.0.0
 */

namespace Core;

class Router
{
    /**
     * Array de rotas registradas
     * @var array
     */
    protected $routes = [];

    /**
     * Parâmetros extraídos da URL
     * @var array
     */
    protected $params = [];

    /**
     * Registrar rota GET
     * 
     * @param string $path Caminho da rota
     * @param string $controller Controlador
     * @param string $action Ação
     * @return void
     */
    public function get($path, $controller, $action)
    {
        $this->addRoute('GET', $path, $controller, $action);
    }

    /**
     * Registrar rota POST
     * 
     * @param string $path Caminho da rota
     * @param string $controller Controlador
     * @param string $action Ação
     * @return void
     */
    public function post($path, $controller, $action)
    {
        $this->addRoute('POST', $path, $controller, $action);
    }

    /**
     * Registrar rota PUT
     * 
     * @param string $path Caminho da rota
     * @param string $controller Controlador
     * @param string $action Ação
     * @return void
     */
    public function put($path, $controller, $action)
    {
        $this->addRoute('PUT', $path, $controller, $action);
    }

    /**
     * Registrar rota DELETE
     * 
     * @param string $path Caminho da rota
     * @param string $controller Controlador
     * @param string $action Ação
     * @return void
     */
    public function delete($path, $controller, $action)
    {
        $this->addRoute('DELETE', $path, $controller, $action);
    }

    /**
     * Adicionar rota
     * 
     * @param string $method Método HTTP
     * @param string $path Caminho
     * @param string $controller Controlador
     * @param string $action Ação
     * @return void
     */
    protected function addRoute($method, $path, $controller, $action)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Despachar requisição
     * 
     * @return void
     */
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $this->getUrl();

        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $method, $url)) {
                return $this->callController(
                    $route['controller'],
                    $route['action']
                );
            }
        }

        // Rota não encontrada
        http_response_code(404);
        echo "404 - Página não encontrada";
        exit;
    }

    /**
     * Obter URL da requisição
     * 
     * @return string
     */
    protected function getUrl()
    {
        $url = $_GET['url'] ?? '/';
        return '/' . trim($url, '/');
    }

    /**
     * Verificar se rota corresponde
     * 
     * @param array $route Rota
     * @param string $method Método HTTP
     * @param string $url URL
     * @return bool
     */
    protected function matchRoute($route, $method, $url)
    {
        if ($route['method'] !== $method) {
            return false;
        }

        $pattern = $this->convertPathToRegex($route['path']);

        if (preg_match($pattern, $url, $matches)) {
            // Extrair parâmetros
            array_shift($matches);
            $this->params = $matches;
            return true;
        }

        return false;
    }

    /**
     * Converter caminho para regex
     * 
     * @param string $path Caminho
     * @return string
     */
    protected function convertPathToRegex($path)
    {
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    /**
     * Chamar controlador e ação
     * 
     * @param string $controller Nome do controlador
     * @param string $action Nome da ação
     * @return void
     */
    protected function callController($controller, $action)
    {
        $controllerClass = "App\\Controllers\\" . $controller;

        if (!class_exists($controllerClass)) {
            http_response_code(404);
            echo "Controlador não encontrado: {$controller}";
            exit;
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $action)) {
            http_response_code(404);
            echo "Ação não encontrada: {$action}";
            exit;
        }

        call_user_func_array(
            [$controllerInstance, $action],
            $this->params
        );
    }

    /**
     * Obter parâmetros extraídos
     * 
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
