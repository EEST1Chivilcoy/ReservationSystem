<?php
require "../include/VerificacionAdmin.php";
include("../include/conexion.php");

$p_ID = htmlspecialchars($_GET['ID'], ENT_QUOTES, 'UTF-8');
$p_curso = htmlspecialchars($_GET['curso'], ENT_QUOTES, 'UTF-8');
$p_materia = htmlspecialchars($_GET['materia'], ENT_QUOTES, 'UTF-8');
$p_horario = htmlspecialchars($_GET['horario'], ENT_QUOTES, 'UTF-8');
$p_horario1 = htmlspecialchars($_GET['horario1'], ENT_QUOTES, 'UTF-8');
$p_fecha = htmlspecialchars($_GET['fecha'], ENT_QUOTES, 'UTF-8');
$p_info = htmlspecialchars($_GET['info'], ENT_QUOTES, 'UTF-8');
$p_materiales = htmlspecialchars($_GET['materiales'], ENT_QUOTES, 'UTF-8');

$modifica = "UPDATE tabla SET curso = ?, materia = ?, horario = ?, horario1 = ?, fecha = ?, info = ?, materiales = ? WHERE ID = ?";
$stmt = mysqli_prepare($conexion, $modifica);

if ($stmt) {
   mysqli_stmt_bind_param($stmt, "ssssssss", $p_curso, $p_materia, $p_horario, $p_horario1, $p_fecha, $p_info, $p_materiales, $p_ID);
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

mysqli_close($conexion);