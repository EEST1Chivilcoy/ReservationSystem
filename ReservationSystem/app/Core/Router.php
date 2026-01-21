<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Quitar query params de la ruta
        $path = parse_url($path, PHP_URL_PATH);
        
        // Hacer match con las rutas definidas
        // Nota: Esto es un enrutador muy simple. En producción se requeriría algo más robusto para base path, etc.
        
        // Si la aplicación no está en la raíz del servidor, se debe ajustar el path
        // Por ahora asumimos que todo corre desde public/index.php y se controla con .htaccess
        
        // Normalizar path (asegurar que empiece con /)
        if ($path !== '/') {
             $path = rtrim($path, '/');
        }

        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            http_response_code(404);
            require_once '../app/Views/not_found.php'; // Necesitaremos crear esta vista
            return;
        }

        if (is_array($callback)) {
            $controller = new $callback[0]();
            $action = $callback[1];
            
            // Aquí se podrían pasar parámetros de la URL al método
            $controller->$action();
        } else {
            call_user_func($callback);
        }
    }
}
