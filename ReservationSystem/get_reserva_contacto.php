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

// Misma lógica de JOIN que cancelar_reserva_sql.php para máxima consistencia
$stmt = $conexion->prepare("SELECT r.nombreapellido, 
                                   COALESCE(u.telefono, u2.telefono) AS telefono, 
                                   COALESCE(u.tipo_telefono, u2.tipo_telefono) AS tipo_telefono,
                                   COALESCE(u.NombreYApellido, u2.NombreYApellido, r.nombreapellido) AS NombreYApellido
                            FROM tabla r 
                            LEFT JOIN usuarios u ON r.id_usuario = u.ID
                            LEFT JOIN usuarios u2 ON r.nombreapellido = u2.NombreYApellido
                            WHERE r.ID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$reserva = $result->fetch_assoc();
$stmt->close();
mysqli_close($conexion);

if (!$reserva) {
    http_response_code(404);
    echo json_encode(['error' => 'Reserva no encontrada']);
    exit();
}

header('Content-Type: application/json');
echo json_encode([
    'telefono' => $reserva['telefono'] ?? '',
    'tipo_telefono' => $reserva['tipo_telefono'] ?? '',
    'nombreapellido' => $reserva['NombreYApellido'] ?? $reserva['nombreapellido'] ?? ''
]);
?>
