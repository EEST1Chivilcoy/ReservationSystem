<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pdfFile = $data['pdf'];

    if (file_exists($pdfFile)) {
        if (unlink($pdfFile)) {
            echo json_encode(['success' => 'PDF eliminado correctamente']);
        } else {
            echo json_encode(['error' => 'Error al eliminar el PDF']);
        }
    } else {
        echo json_encode(['error' => 'El archivo PDF no existe']);
    }
}
?>