<?php
require "../include/VerificacionAdmin.php";
include("../include/conexion.php");

$p_ID = $_POST['ID'];
$p_curso = $_POST['curso'];
$p_materia = $_POST['materia'];
$p_horario = $_POST['horario'];
$p_horario1 = $_POST['horario1'];
$p_fecha = $_POST['fecha'];
$p_info = $_POST['info'];
$p_materiales = $_POST['materiales'];
$p_boton = $_POST['boton'];

if ($p_boton == 0) {
   header("Location: ../index.php");
   exit();
} else {
   $p_ID =  mysqli_real_escape_string($conexion, $p_ID);
   $modifica = "UPDATE tabla SET curso='$p_curso',materia='$p_materia',horario='$p_horario', horario1='$p_horario1',fecha='$p_fecha',info='$p_info', materiales='$p_materiales' WHERE ID='$p_ID'";

   $resultado_modifica = mysqli_query($conexion, $modifica);

   if($resultado_modifica){
      header("Location: ../index.php");
      exit();
   }
   else{
      // Manejar el caso en que la edicion falló
      echo "Error al intentar editar el registro.";
   }
}
