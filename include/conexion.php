<?php
// Cargar el archivo de configuración
$config = require '../config.php'; // Asegúrate de colocar la ruta correcta

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

// Seleccionar la base de datos
$db = mysqli_select_db($conexion, $basededatos);

// Verificar la selección de la base de datos
if (!$db) {
    die('No se pudo seleccionar la base de datos');
}

// Ahora puedes utilizar la conexión $conexion para realizar consultas SQL

?>
