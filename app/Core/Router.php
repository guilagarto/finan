<?php

namespace App\Core;

class Router {
    private array $routes = [];

    // Cadastra uma rota do tipo GET
    public function get(string $path, string $controllerAction): void {
        $this->routes['GET'][$path] = $controllerAction;
    }

    // Cadastra uma rota do tipo POST (usada para formulários como o de login)
    public function post(string $path, string $controllerAction): void {
        $this->routes['POST'][$path] = $controllerAction;
    }

    // Processa a requisição atual do navegador
    public function run(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove o prefixo da subpasta do XAMPP de forma segura
        $subpasta = BASE_URL;

        // Só remove se a subpasta não estiver vazia e a URL realmente começar com ela
        if (!empty($subpasta) && str_starts_with($url, $subpasta)) {
            $url = substr($url, strlen($subpasta));
        }

        // Garante que se a URL ficar vazia, vire a raiz "/"
        if (empty($url) || $url === '') {
            $url = '/';
        }

        // Garante que a URL comece com "/" se não estiver vazia
        if ($url !== '/' && !str_starts_with($url, '/')) {
            $url = '/' . $url;
        }

        // Remove a barra final se houver (ex: /login/ vira /login)
        if ($url !== '/' && str_ends_with($url, '/')) {
            $url = rtrim($url, '/');
        }

        // Procura a rota correspondente
        if (isset($this->routes[$method][$url])) {
            $action = $this->routes[$method][$url];
            $this->executeAction($action);
            return;
        }

        // Se não encontrar, exibe o erro 404 com diagnóstico
        http_response_code(404);
        echo "Página não encontrada (404). URL buscada internamente: " . $url;
    }

    // Executa o controlador e o método mapeados na rota
    private function executeAction(string $action): void {
        list($controllerName, $method) = explode('@', $action); 
        $fullControllerName = "\\App\\Controllers\\" . $controllerName;

        if (class_exists($fullControllerName)) {
            $controller = new $fullControllerName();
            if (method_exists($controller, $method)) {
                $controller->$method();
                return;
            }
        }

        http_response_code(500);
        echo "Erro interno: Controller ou método não encontrado. Tentou chamar: " . $fullControllerName . " -> " . $method;
    }
}
