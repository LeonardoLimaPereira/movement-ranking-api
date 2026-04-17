<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Response; 
use App\Core\Router;

$container = require __DIR__ . '/../bootstrap/app.php';
$router = new Router();

// Carregar rotas
$router->prefix('/api', function ($router) {
    require __DIR__ . '/../routes/api.php';
});


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/' || $uri === '') {
    Response::json(['message' => 'API OK']);
    exit;
}

$router->dispatch($uri, $method, $container);