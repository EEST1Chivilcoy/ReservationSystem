<?php
session_start();
include('../include/conexion.php');

header('Content-Type: application/json');

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true || $_SESSION['EsAdmin'] !== true) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : 'marcar_todas';
    
    if ($action === 'marcar_todas') {
        // Marcar solo las notificaciones dirigidas a administradores como leído
        $query = "UPDATE notificaciones SET leido = 1 WHERE leido = 0 AND para_admins = 1";
        if (mysqli_query($conexion, $query)) {
            echo json_encode(['success' => true, 'message' => 'Notificaciones marcadas como leídas']);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conexion)]);
        }
    } elseif ($action === 'marcar_individual') {
        // Marcar una notificación individual como leída
        $notificacion_id = intval($_POST['notificacion_id'] ?? 0);
        
        if ($notificacion_id > 0) {
            $query = "UPDATE notificaciones SET leido = 1 WHERE ID = ? AND para_admins = 1";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("i", $notificacion_id);
            
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
