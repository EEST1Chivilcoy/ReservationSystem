<?php
require "../include/VerificacionAdmin.php";
include("../include/conexion.php");

$p_ID = mysqli_real_escape_string($conexion, $_POST['ID']);
$p_usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
$p_contra_nohash = $_POST['contraseña'];  // No aplicar escapado aquí si solo usas para la lógica de contraseña
$p_nombre_apellido = mysqli_real_escape_string($conexion, $_POST['nombreyapellido']);

if (isset($_POST['esAdmin'])) {
    $esAdmin = 1;  // El valor será '1' si está marcado
} else {
    $esAdmin = 0;  // El checkbox no está marcado
}

$p_boton = $_POST['boton'];

if ($p_boton == 0) {
    header("Location: gestion.php");
    exit();
} else {
    if ($p_contra_nohash === "********") {
        // Si la contraseña es 8 asteriscos, no cambies la contraseña
        $modifica = "UPDATE usuarios SET usuario='$p_usuario', NombreYApellido='$p_nombre_apellido', esAdmin='$esAdmin' WHERE ID='$p_ID'";
    } else {
        // Si la contraseña no es 8 asteriscos, hashea la nueva contraseña
        $p_contra_hash = password_hash($p_contra_nohash, PASSWORD_DEFAULT);
        $modifica = "UPDATE usuarios SET usuario='$p_usuario', clave='$p_contra_hash', NombreYApellido='$p_nombre_apellido', esAdmin='$esAdmin' WHERE ID='$p_ID'";
    }

    $resultado_modifica = mysqli_query($conexion, $modifica);

    if ($resultado_modifica) {
        header("Location: gestion.php");
        exit();
    } else {
        // Manejar el caso en que la edición falló
        echo "Error al intentar editar el registro.";
    }
}
?>
