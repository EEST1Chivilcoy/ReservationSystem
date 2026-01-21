<?php

/** @var \App\Core\Router $router */

$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/events', [\App\Controllers\HomeController::class, 'getEvents']);

$router->get('/login', [\App\Controllers\AuthController::class, 'login']);
$router->post('/login', [\App\Controllers\AuthController::class, 'authenticate']);
$router->get('/logout', [\App\Controllers\AuthController::class, 'logout']);
$router->get('/register', [\App\Controllers\AuthController::class, 'register']);
$router->post('/register', [\App\Controllers\AuthController::class, 'store']);

$router->get('/reservations/create', [\App\Controllers\ReservationController::class, 'create']);
$router->post('/reservations/store', [\App\Controllers\ReservationController::class, 'store']);
$router->get('/reservations/edit', [\App\Controllers\ReservationController::class, 'edit']);
$router->post('/reservations/update', [\App\Controllers\ReservationController::class, 'update']);
$router->post('/reservations/delete', [\App\Controllers\ReservationController::class, 'delete']);

$router->get('/admin/users', [\App\Controllers\AdminController::class, 'index']);
$router->post('/admin/users/toggle-role', [\App\Controllers\AdminController::class, 'toggleRole']);
$router->post('/admin/users/delete', [\App\Controllers\AdminController::class, 'deleteUser']);
$router->post('/admin/report', [\App\Controllers\AdminController::class, 'generateReport']);
$router->get('/admin/qr', [\App\Controllers\AdminController::class, 'generateQR']);
