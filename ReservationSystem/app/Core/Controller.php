<?php

namespace App\Core;

abstract class Controller
{
    /**
     * Carga una vista
     * @param string $view Nombre de la vista (ej: 'home/index')
     * @param array $data Datos a pasar a la vista
     */
    protected function view($view, $data = [])
    {
        // Extraer los datos para que sean variables en la vista
        extract($data);

        // Ruta al archivo de vista
        $viewFile = '../app/Views/' . $view . '.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            // Manejo de error si la vista no existe
            die("La vista '$viewFile' no existe.");
        }
    }

    /**
     * Redirecciona a una URL
     * @param string $url
     */
    protected function redirect($url)
    {
        header("Location: " . $url);
        exit();
    }
}
