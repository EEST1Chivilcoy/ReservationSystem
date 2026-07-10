<?php
session_start();
include('../include/conexion.php');

header('Content-Type: application/json');

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true || $_SESSION['EsAdmin'] !== true) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : 'marcar_todas';
    
    if ($action === 'marcar_todas') {
        // Marcar todas las notificaciones para admins como leídas por el usuario actual
        $query = "INSERT IGNORE INTO notificaciones_leidas (id_notificacion, id_usuario, fecha_lectura) SELECT n.ID, ?, NOW() FROM notificaciones n LEFT JOIN notificaciones_leidas nl ON nl.id_notificacion = n.ID AND nl.id_usuario = ? WHERE n.para_admins = 1 AND nl.id_usuario IS NULL";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ii", $usuario_id, $usuario_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Notificaciones marcadas como leídas']);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conexion)]);
        }
        $stmt->close();
    } elseif ($action === 'marcar_individual') {
        // Marcar una notificación individual como leída por el usuario actual
        $notificacion_id = intval($_POST['notificacion_id'] ?? 0);
        
        if ($notificacion_id > 0) {
            $query = "INSERT IGNORE INTO notificaciones_leidas (id_notificacion, id_usuario, fecha_lectura) VALUES (?, ?, NOW())";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("ii", $notificacion_id, $usuario_id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Notificación marcada como leída']);
            } else {
                echo json_encode(['success' => false, 'error' => mysqli_error($conexion)]);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'ID de notificación inválido']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Acción no válida']);
    }
}

mysqli_close($conexion);
?>
