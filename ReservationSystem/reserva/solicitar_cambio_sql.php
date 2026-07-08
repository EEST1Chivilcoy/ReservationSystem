<?php
session_start();
include('../include/conexion.php');

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: ../iniciar_sesion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nueva_fecha = $_POST['nueva_fecha'];
    $nuevo_horario = $_POST['nuevo_horario'];
    $nuevo_horario1 = $_POST['nuevo_horario1'];
    $motivo = htmlspecialchars($_POST['motivo'], ENT_QUOTES, 'UTF-8');
    
    $usuario_id = $_SESSION['usuario_id'];

    // Obtener info de la reserva original
    $stmt = $conexion->prepare("SELECT info, fecha, horario FROM tabla WHERE ID = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reserva = $result->fetch_assoc();
    $stmt->close();

    if (!$reserva) {
        header("Location: ../mis_reservas.php?error=Reserva no encontrada");
        exit();
    }

    $hoy = date('Y-m-d');
    if ($reserva['fecha'] < $hoy) {
        header("Location: ../mis_reservas.php?error=No se puede modificar una reserva pasada");
        exit();
    }

    // Insertar notificación para administradores
    $fecha_formateada_vieja = date('d/m/Y', strtotime($reserva['fecha']));
    $fecha_formateada_nueva = date('d/m/Y', strtotime($nueva_fecha));
    
    $mensaje = "solicita cambiar su reserva de " . $reserva['info'] . " del " . $fecha_formateada_vieja . " (" . $reserva['horario'] . ") al " . $fecha_formateada_nueva . " (" . $nuevo_horario . " - " . $nuevo_horario1 . "). Motivo: " . $motivo;
    
    $stmt = $conexion->prepare("INSERT INTO notificaciones (id_usuario_origen, tipo, mensaje, leido, fecha) VALUES (?, 'cambio_fecha', ?, 0, NOW())");
    $stmt->bind_param("is", $usuario_id, $mensaje);
    $stmt->execute();
    $stmt->close();

    header("Location: ../mis_reservas.php?success=" . urlencode("Solicitud de cambio enviada correctamente. Los administradores la revisarán pronto."));
    exit();
}
?>
