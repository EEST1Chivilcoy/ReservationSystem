<?php
// Cargar el archivo de configuración
$config = require '../config.php'; // Ajusta la ruta según la estructura de tus directorios

// Obtener los valores de la configuración
$usuario = $config['db']['usuario'];
$clave = $config['db']['clave'];
$servidor = $config['db']['servidor'];
$basededatos = $config['db']['basededatos'];

// Establecer la conexión con la base de datos
$conexion = mysqli_connect($servidor, $usuario, $clave, $basededatos);

// Verificar la conexión
if (!$conexion) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// Ahora puedes utilizar la conexión $conexion para realizar consultas SQL
?>
