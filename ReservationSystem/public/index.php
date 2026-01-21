<?php

use App\Core\Router;

// 1. Cargar autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

// 3. Iniciar sesión
session_start();

// 4. Configurar manejo de errores
if ($_ENV['APP_DEBUG'] ?? false) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    // Log errors instead
}

// 5. Instanciar Router
$router = new Router();

// 6. Cargar rutas
require_once __DIR__ . '/../routes/web.php';

// 7. Despachar
$router->dispatch();
