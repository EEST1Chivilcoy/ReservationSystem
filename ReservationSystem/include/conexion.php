<?php
// Cargar autoload de Composer
require_once dirname(__DIR__, 1) . '/vendor/autoload.php';

// Cargar variables de entorno desde .env
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Obtener los valores desde .env
$usuario = $_ENV['DB_USER'];
$clave = $_ENV['DB_PASS'];
$servidor = $_ENV['DB_HOST'];
$basededatos = $_ENV['DB_NAME'];

// Establecer la conexión con la base de datos
$conexion = mysqli_connect($servidor, $usuario, $clave, $basededatos);

// Verificar la conexión
if (!$conexion) {
    die('Error de conexión: ' . mysqli_connect_error());
}

// Establecer el conjunto de caracteres a utf8mb4
mysqli_set_charset($conexion, 'utf8mb4');

// Ahora puedes utilizar la conexión $conexion para realizar consultas SQL
?>