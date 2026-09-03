<?php
// Exibe erros na tela durante o desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia a sessão global do sistema
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// CONFIGURAÇÃO DE URL DINÂMICA (LOCAL VS WEB)
// ==========================================
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_ADDR'] === '127.0.0.1') {
    define('BASE_URL', '/financas-app');
} else {
    define('BASE_URL', ''); // Fica vazio na Hostinger porque roda direto na raiz do domínio
}

// Função global para gerar links corretos em qualquer ambiente
if (!function_exists('url')) {
    function url(string $path = ''): string {
        return BASE_URL . '/' . ltrim($path, '/');
    }
}
// ==========================================

// 1. Carrega o Autoload do Composer de forma dinâmica e segura
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
// Backup caso a estrutura mude na hospedagem
$hostingerAutoload = '/home/u730627255/domains/8ou80.xyz/public_html/vendor/autoload.php';

if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
} elseif (file_exists($hostingerAutoload)) {
    require_once $hostingerAutoload;
}

// 2. Inclui o arquivo de rotas
$routesPath = __DIR__ . '/../app/Config/routes.php';
$hostingerRoutes = '/home/u730627255/domains/8ou80.xyz/public_html/app/Config/routes.php';

if (file_exists($routesPath)) {
    require_once $routesPath;
} elseif (file_exists($hostingerRoutes)) {
    require_once $hostingerRoutes;
}

// 3. Inicializa o roteamento global
if (isset($router) && $router instanceof \App\Core\Router) {
    $router->run();
} else {
    echo "Erro Fatal: O roteador não pôde ser iniciado.";
}
