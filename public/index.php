<?php
// Exibe erros na tela durante o desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia a sessão global do sistema
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
$routesPath = __DIR__ . '/../app/Config/routes.php'; // Verifique se o caminho local das suas rotas é este mesmo
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
