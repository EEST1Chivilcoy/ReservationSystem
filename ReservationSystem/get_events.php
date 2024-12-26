<?php
include('include/conexion.php');

$query = "SELECT ID, nombreapellido, curso, materia, horario, horario1, fecha, info, materiales 
          FROM tabla";

$result = mysqli_query($conexion, $query);
$events = array();

while ($row = mysqli_fetch_assoc($result)) {
    $events[] = array(
        'id' => $row['ID'],
        'title' => $row['info'] . ' - ' . $row['nombreapellido'],
        'start' => $row['fecha'] . 'T' . $row['horario'],
        'end' => $row['fecha'] . 'T' . $row['horario1'],
        'extendedProps' => array(
            'id' => $row['ID'],
            'nombreapellido' => $row['nombreapellido'],
            'curso' => $row['curso'],
            'materia' => $row['materia'],
            'info' => $row['info'],
            'materiales' => $row['materiales']
        )
    );
}

header('Content-Type: application/json');
echo json_encode($events);