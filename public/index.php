<?php

// 1. Carrega o Autoload do Composer voltando uma pasta de forma dinâmica
$autoloadPath = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

// 2. Inclui o arquivo de rotas voltando uma pasta de forma dinâmica
$routesPath = dirname(__DIR__) . '/app/Config/routes.php';
if (file_exists($routesPath)) {
    require_once $routesPath;
}

// 3. Inicializa o roteamento global
if (isset($router) && $router instanceof \App\Core\Router) {
    $router->run();
} else {
    echo "Erro Fatal: O roteador não pôde ser iniciado.";
}
