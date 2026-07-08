<?php
session_start();
include('../include/conexion.php');

header('Content-Type: application/json');

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true || $_SESSION['EsAdmin'] !== true) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $query = "UPDATE notificaciones SET leido = 1 WHERE leido = 0";
    if (mysqli_query($conexion, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conexion)]);
    }
}

mysqli_close($conexion);
?>
