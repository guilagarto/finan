<?php
// Exibe erros na tela durante o desenvolvimento
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia a sessão global do sistema
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carrega o Autoload do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// CORREÇÃO: Puxa o arquivo de rotas apontando para a pasta Config com "C" maiúsculo
$router = require_once __DIR__ . '/../app/Config/routes.php';

// Inicia o motor de rotas
$router->run();
