<?php
include('include/conexion.php');

// FullCalendar envía estos parámetros automáticamente
$start = isset($_GET['start']) ? $_GET['start'] : date('Y-m-01');
$end = isset($_GET['end']) ? $_GET['end'] : date('Y-m-t', strtotime('+1 month'));

// Convertir formato ISO a fecha MySQL
$start_date = date('Y-m-d', strtotime($start));
$end_date = date('Y-m-d', strtotime($end));

// Consulta optimizada: solo eventos en el rango visible
$query = "SELECT ID, nombreapellido, curso, materia, horario, horario1, fecha, info, materiales 
          FROM tabla 
          WHERE fecha BETWEEN ? AND ?
          ORDER BY fecha, horario";

$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

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

mysqli_stmt_close($stmt);
mysqli_close($conexion);

header('Content-Type: application/json');
echo json_encode($events);
?>