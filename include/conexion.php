<?php
// Incluye el archivo de configuración
require '../config.php'; // Ajusta la ruta según la estructura de tus directorios

// Variables de configuración obtenidas desde config.php
$usuario = $usuario;
$clave = $clave;
$servidor = $servidor;
$basededatos = $basededatos;

// Establecer la conexión con la base de datos
$conexion = mysqli_connect($servidor, $usuario, $clave, $basededatos);

// Verificar la conexión
if (!$conexion) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// Ahora puedes utilizar la conexión $conexion para realizar consultas SQL
?>
