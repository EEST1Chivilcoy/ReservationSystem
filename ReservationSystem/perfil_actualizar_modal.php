<?php
session_start();
include('include/conexion.php');

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: iniciar_sesion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono = htmlspecialchars($_POST['telefono'], ENT_QUOTES, 'UTF-8');
    $tipo_telefono = htmlspecialchars($_POST['tipo_telefono'], ENT_QUOTES, 'UTF-8');
    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conexion->prepare("UPDATE usuarios SET telefono = ?, tipo_telefono = ?, modal_v2_visto = 1 WHERE ID = ?");
    $stmt->bind_param("ssi", $telefono, $tipo_telefono, $usuario_id);
    
    if ($stmt->execute()) {
        $_SESSION['telefono'] = $telefono;
        $_SESSION['tipo_telefono'] = $tipo_telefono;
        $_SESSION['modal_v2_visto'] = 1;
    }
    $stmt->close();
}

mysqli_close($conexion);
header("Location: index.php");
exit();
?>
