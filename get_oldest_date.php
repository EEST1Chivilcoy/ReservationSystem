<?php
header('Content-Type: application/json');
include('include\conexion.php'); // Incluir la conexión existente

$sql = "SELECT MIN(fecha) as oldest_date FROM tabla";
$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["oldest_date" => $row['oldest_date']]);
} else {
    echo json_encode(["error" => "No se encontraron datos."]);
}

$conexion->close();
?>