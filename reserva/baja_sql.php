<?php

require "../include/VerificacionAdmin.php";
include("../include/conexion.php");

//recibo los valores
$p_ID = $_GET['id'];

// Sanitizar y preparar la ID para evitar inyección SQL
$p_ID = mysqli_real_escape_string($conexion, $p_ID);
$baja = "DELETE FROM tabla WHERE ID='$p_ID'";
// Ejecutar la consulta
$resultado_baja = mysqli_query($conexion, $baja);
// Verificar si la consulta se ejecutó correctamente
if ($resultado_baja) {
    // Redireccionar al index si la eliminación fue exitosa
    header("Location: ../index.php");
    exit();
} else {
    // Manejar el caso en que la eliminación falló
    echo "Error al intentar eliminar el registro.";
}

?>