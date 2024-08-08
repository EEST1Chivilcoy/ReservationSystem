<?php
// Cargar el archivo de configuración
$config = require 'config_database.php';

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

$mysqli->set_charset("utf8mb4");

// Ahora puedes utilizar la conexión $conexion para realizar consultas SQL
?>