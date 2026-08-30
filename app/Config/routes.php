<?php

use App\Core\Router;

$router = new Router();

// Rotas de Autenticação
$router->get('/', 'AuthController@showLogin');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// ADICIONE ESTAS DUAS LINHAS AQUI PARA LIBERAR O CADASTRO:
$router->get('/cadastro', 'AuthController@showCadastro');
$router->post('/cadastro', 'AuthController@register');

// Rotas da Dashboard
$router->get('/dashboard', 'DashboardController@index');
$router->get('/dashboard/mes', 'DashboardController@mes');
$router->get('/dashboard/transacao/nova', 'DashboardController@novaTransacao');
$router->post('/dashboard/transacao/salvar', 'DashboardController@salvarTransacao');

// Certifique-se de que esta linha está presente no seu routes.php:
$router->get('/dashboard/transacao/excluir', 'DashboardController@excluirTransacao');


return $router;
