<?php
require "../include/VerificacionAdmin.php";
include("../include/conexion.php");

//recibo los valores
$p_ID = $_POST['ID'];
$p_boton = $_POST['boton'];

//Estructura de decision

if ($p_boton == 0) {
    header("Location: gestion.php");
    exit();
} else {
    // Sanitizar y preparar la ID para evitar inyección SQL
    $p_ID = mysqli_real_escape_string($conexion, $p_ID);
    $baja = "DELETE FROM usuarios WHERE ID='$p_ID'";
    // Ejecutar la consulta
    $resultado_baja = mysqli_query($conexion, $baja);
    // Verificar si la consulta se ejecutó correctamente
    if ($resultado_baja) {
        // Redireccionar al index si la eliminación fue exitosa
        header("Location: gestion.php");
        exit();
    } else {
        // Manejar el caso en que la eliminación falló
        echo "Error al intentar eliminar el registro.";
    }
}

?>