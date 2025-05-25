<?php
require "../include/VerificacionAdmin.php";
include("../include/conexion.php");

// Recibo el ID
$p_ID = $_GET['id'] ?? null;

if (!$p_ID) {
    echo "Error: No se proporcionó un ID válido.";
    exit();
}

// Sanitizar y preparar la ID para evitar inyección SQL
$p_ID = mysqli_real_escape_string($conexion, $p_ID);
$baja = "DELETE FROM usuarios WHERE ID='$p_ID'";

// Ejecutar la consulta
if (mysqli_query($conexion, $baja)) {
    header("Location: gestion.php");
    exit();
} else {
    echo "Error al intentar eliminar el registro: " . mysqli_error($conexion);
}

?>