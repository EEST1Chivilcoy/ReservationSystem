<?php

/** @var \App\Core\Router $router */

$router->get('/', function() {
    echo "¡Hola desde MVC! El sistema se está migrando.";
});

$router->get('/login', [\App\Controllers\AuthController::class, 'login']);
$router->post('/login', [\App\Controllers\AuthController::class, 'authenticate']);
$router->get('/logout', [\App\Controllers\AuthController::class, 'logout']);
$router->get('/register', [\App\Controllers\AuthController::class, 'register']);
$router->post('/register', [\App\Controllers\AuthController::class, 'store']);
