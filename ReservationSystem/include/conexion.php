<?php
// Cargar autoload de Composer
require_once dirname(__DIR__, 1) . '/vendor/autoload.php';

// Intentar cargar variables desde .env si existe
$dotenvPath = dirname(__DIR__) . '/.env';
if (file_exists($dotenvPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->safeLoad(); // safeLoad no lanza excepción si falta el archivo
}

// Obtener valores de entorno (desde .env o desde variables de Docker)
$usuario     = $_ENV['DB_USER']     ?? getenv('DB_USER')     ?? 'root';
$clave       = $_ENV['DB_PASS']     ?? getenv('DB_PASS')     ?? '';
$servidor    = $_ENV['DB_HOST']     ?? getenv('DB_HOST')     ?? 'localhost';
$basededatos = $_ENV['DB_NAME']     ?? getenv('DB_NAME')     ?? 'test';

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