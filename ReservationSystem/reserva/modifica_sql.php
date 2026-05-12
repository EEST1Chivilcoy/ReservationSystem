<?php
require "../include/VerificacionAdmin.php";
include("../include/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_ID = htmlspecialchars($_POST['ID'], ENT_QUOTES, 'UTF-8');
    $p_curso = htmlspecialchars($_POST['curso'], ENT_QUOTES, 'UTF-8');
    $p_materia = htmlspecialchars($_POST['materia'], ENT_QUOTES, 'UTF-8');
    $p_horario = htmlspecialchars($_POST['horario'], ENT_QUOTES, 'UTF-8');
    $p_horario1 = htmlspecialchars($_POST['horario1'], ENT_QUOTES, 'UTF-8');
    $p_fecha = htmlspecialchars($_POST['fecha'], ENT_QUOTES, 'UTF-8');
    $p_info = htmlspecialchars($_POST['info'], ENT_QUOTES, 'UTF-8');
    $p_materiales = htmlspecialchars($_POST['materiales'], ENT_QUOTES, 'UTF-8');

    if (!in_array($p_curso, ["Reunión", "Charla/Conferencia", "Acto", "Charla/conferencia"])) {
        $division = isset($_POST["division"]) ? htmlspecialchars($_POST["division"], ENT_QUOTES, 'UTF-8') : "";
        $p_curso = $p_curso . " " . $division;
    }

    if ($p_info == "Otro") {
        $otro_salon = isset($_POST["otro_salon"]) ? htmlspecialchars($_POST["otro_salon"], ENT_QUOTES, 'UTF-8') : "";
        $p_info = $otro_salon;
    }

    // Validar si ya existe una reserva en el mismo salón, fecha y horario, que no sea esta misma
    $consulta = "SELECT COUNT(*) as total FROM tabla WHERE info = ? AND fecha = ? AND ID != ? AND ((horario < ? AND horario1 > ?) OR (horario >= ? AND horario < ?))";
    $stmt_check = mysqli_prepare($conexion, $consulta);
    if ($stmt_check) {
        mysqli_stmt_bind_param($stmt_check, "ssissss", $p_info, $p_fecha, $p_ID, $p_horario1, $p_horario, $p_horario, $p_horario1);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_bind_result($stmt_check, $total_reservas);
        mysqli_stmt_fetch($stmt_check);
        mysqli_stmt_close($stmt_check);

        if ($total_reservas > 0) {
            $Message = "El salón ya está reservado en la fecha y horario seleccionados.";
            header("Location: ../index.php?error=" . urlencode($Message));
            exit();
        }
    } else {
        $Message = "Error en la preparación de la consulta de validación.";
        header("Location: ../index.php?error=" . urlencode($Message));
        exit();
    }

    $modifica = "UPDATE tabla SET curso = ?, materia = ?, horario = ?, horario1 = ?, fecha = ?, info = ?, materiales = ? WHERE ID = ?";
    $stmt = mysqli_prepare($conexion, $modifica);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssi", $p_curso, $p_materia, $p_horario, $p_horario1, $p_fecha, $p_info, $p_materiales, $p_ID);
        $resultado_modifica = mysqli_stmt_execute($stmt);

        if ($resultado_modifica) {
            header("Location: ../index.php?success=" . urlencode("Registro modificado correctamente."));
            exit();
        } else {
            $Message = "Error al intentar editar el registro.";
            header("Location: ../index.php?error=" . urlencode($Message));
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        $Message = "Error en la preparación de la consulta de modificación.";
        header("Location: ../index.php?error=" . urlencode($Message));
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}

mysqli_close($conexion);