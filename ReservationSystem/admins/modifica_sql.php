<?php
require "../include/VerificacionAdmin.php";
include("../include/conexion.php");

// Validar y sanitizar los datos recibidos por GET
$p_ID = filter_input(INPUT_GET, 'ID', FILTER_SANITIZE_NUMBER_INT);
$p_usuario = filter_input(INPUT_GET, 'usuario', FILTER_SANITIZE_STRING);
$p_contra_nohash = filter_input(INPUT_GET, 'contraseña', FILTER_DEFAULT); // No sanitizar para lógica de contraseña
$p_nombre_apellido = filter_input(INPUT_GET, 'nombreyapellido', FILTER_SANITIZE_STRING);
$esAdmin = filter_input(INPUT_GET, 'esAdmin', FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

if ($p_contra_nohash === "********") {
    // Si la contraseña es 8 asteriscos, no cambies la contraseña
    $query = "UPDATE usuarios SET usuario = ?, NombreYApellido = ?, esAdmin = ? WHERE ID = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssii", $p_usuario, $p_nombre_apellido, $esAdmin, $p_ID);
} else {
    // Si la contraseña no es 8 asteriscos, hashea la nueva contraseña
    $p_contra_hash = password_hash($p_contra_nohash, PASSWORD_DEFAULT);
    $query = "UPDATE usuarios SET usuario = ?, clave = ?, NombreYApellido = ?, esAdmin = ? WHERE ID = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sssii", $p_usuario, $p_contra_hash, $p_nombre_apellido, $esAdmin, $p_ID);
}

if ($stmt->execute()) {
    header("Location: gestion.php");
    exit();
} else {
    // Manejar el caso en que la edición falló
    echo "Error al intentar editar el registro: " . $stmt->error;
}

$stmt->close();
$conexion->close();
