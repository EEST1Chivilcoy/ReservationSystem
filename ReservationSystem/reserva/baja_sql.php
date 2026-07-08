<?php

require "../include/VerificacionSesion.php";
include("../include/conexion.php");

//recibo los valores
$p_ID = $_GET['id'];
$p_ID = mysqli_real_escape_string($conexion, $p_ID);

// Verificar permisos
$esAdmin = isset($_SESSION['EsAdmin']) && $_SESSION['EsAdmin'] === true;
$usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : -1;

$consulta_propietario = "SELECT id_usuario FROM tabla WHERE ID = '$p_ID'";
$res_prop = mysqli_query($conexion, $consulta_propietario);
$puedeEliminar = false;

if ($row = mysqli_fetch_assoc($res_prop)) {
    if ($esAdmin || $row['id_usuario'] == $usuario_id) {
        $puedeEliminar = true;
    }
}

if (!$puedeEliminar) {
    header("Location: ../index.php?error=" . urlencode("No tienes permiso para eliminar esta reserva."));
    exit();
}

$baja = "DELETE FROM tabla WHERE ID='$p_ID'";
$resultado_baja = mysqli_query($conexion, $baja);

if ($resultado_baja) {
    // Si viene de mis_reservas.php, volver ahí
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'mis_reservas.php') !== false) {
        header("Location: ../mis_reservas.php?success=" . urlencode("Reserva eliminada correctamente."));
    } else {
        header("Location: ../index.php");
    }
    exit();
} else {
    echo "Error al intentar eliminar el registro.";
}
?>