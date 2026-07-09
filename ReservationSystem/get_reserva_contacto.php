<?php
session_start();
include('include/conexion.php');

// Solo admins pueden acceder a este endpoint
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true || !isset($_SESSION['EsAdmin']) || $_SESSION['EsAdmin'] != true) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
    exit();
}

$id = intval($_GET['id']);

// Primero obtener los datos de la reserva
$stmt = $conexion->prepare("SELECT id_usuario, nombreapellido FROM tabla WHERE ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$reserva_base = $result->fetch_assoc();
$stmt->close();

if (!$reserva_base) {
    http_response_code(404);
    echo json_encode(['error' => 'Reserva no encontrada']);
    mysqli_close($conexion);
    exit();
}

// Ahora buscar los datos del usuario por id_usuario
$telefono = '';
$tipo_telefono = '';
$nombreapellido = $reserva_base['nombreapellido'];

if (!empty($reserva_base['id_usuario'])) {
    $stmt = $conexion->prepare("SELECT telefono, tipo_telefono, NombreYApellido FROM usuarios WHERE ID = ?");
    $stmt->bind_param("i", $reserva_base['id_usuario']);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    $stmt->close();
    
    if ($usuario) {
        $telefono = $usuario['telefono'] ?? '';
        $tipo_telefono = $usuario['tipo_telefono'] ?? '';
        $nombreapellido = $usuario['NombreYApellido'] ?? $nombreapellido;
    }
}

mysqli_close($conexion);

header('Content-Type: application/json');
echo json_encode([
    'telefono' => $telefono,
    'tipo_telefono' => $tipo_telefono,
    'nombreapellido' => $nombreapellido
]);
?>
