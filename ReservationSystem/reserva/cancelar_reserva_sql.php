<?php
session_start();
include('../include/conexion.php');

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: ../iniciar_sesion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $motivo = htmlspecialchars($_POST['motivo'], ENT_QUOTES, 'UTF-8');
    $usuario_id = $_SESSION['usuario_id'];
    $esAdmin = isset($_SESSION['EsAdmin']) && $_SESSION['EsAdmin'] == true;

    // Obtener info de la reserva (con fallback por coincidencia de nombre y apellido)
    $stmt = $conexion->prepare("SELECT r.*, COALESCE(u.telefono, u2.telefono) AS telefono, COALESCE(u.tipo_telefono, u2.tipo_telefono) AS tipo_telefono, COALESCE(u.NombreYApellido, u2.NombreYApellido, r.nombreapellido) AS NombreYApellido FROM tabla r LEFT JOIN usuarios u ON r.id_usuario = u.ID LEFT JOIN usuarios u2 ON r.nombreapellido = u2.NombreYApellido WHERE r.ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reserva = $result->fetch_assoc();
    $stmt->close();

    if (!$reserva) {
        header("Location: ../mis_reservas.php?error=" . urlencode("Reserva no encontrada"));
        exit();
    }

    // Si no es admin y no es dueño de la reserva
    if (!$esAdmin && $reserva['id_usuario'] != $usuario_id) {
        header("Location: ../mis_reservas.php?error=" . urlencode("No tienes permiso para cancelar esta reserva"));
        exit();
    }

    // Verificar que no sea pasada
    $hoy = date('Y-m-d');
    if ($reserva['fecha'] < $hoy) {
        header("Location: ../mis_reservas.php?error=" . urlencode("No se puede cancelar una reserva que ya pasó"));
        exit();
    }

    $id_usuario_reserva = $reserva['id_usuario'] ? $reserva['id_usuario'] : 0;
    
    // Guardar en reservas_canceladas
    $stmt = $conexion->prepare("INSERT INTO reservas_canceladas (id_usuario_origen, id_cancelador, info_reserva, motivo, fecha_cancelacion, notificacion_enviada) VALUES (?, ?, ?, ?, NOW(), 0)");
    $stmt->bind_param("iiss", $id_usuario_reserva, $usuario_id, $reserva['info'], $motivo);
    if (!$stmt->execute()) {
        header("Location: ../mis_reservas.php?error=" . urlencode("Error al guardar la cancelación"));
        exit();
    }
    $stmt->close();

    // Eliminar de tabla
    $stmt = $conexion->prepare("DELETE FROM tabla WHERE ID = ?");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        header("Location: ../mis_reservas.php?error=" . urlencode("Error al eliminar la reserva"));
        exit();
    }
    $stmt->close();

    // Determinar el destino de redirección y notificación
    if ($esAdmin && $reserva['id_usuario'] != $usuario_id) {
        // Admin canceló la reserva de un usuario
        // El admin debe notificar al usuario via WhatsApp o teléfono
        
        // Insertar notificación para el usuario
        $mensaje_notif = "Un administrador ha cancelado tu reserva de " . $reserva['info'] . " (" . date('d/m/Y', strtotime($reserva['fecha'])) . "). Motivo: " . $motivo;
        $stmt = $conexion->prepare("INSERT INTO notificaciones (id_usuario_origen, tipo, mensaje, para_admins, leido, fecha) VALUES (?, 'cancelacion', ?, 0, 0, NOW())");
        $stmt->bind_param("is", $id_usuario_reserva, $mensaje_notif);
        $stmt->execute();
        $stmt->close();

        // Si el usuario tiene teléfono, ir a cancelar_whatsapp para que el admin notifique
        if ($reserva['telefono']) {
            $mensaje_contacto = "Tu reserva de " . $reserva['info'] . " para el " . date('d/m/Y', strtotime($reserva['fecha'])) . " ha sido cancelada. Motivo: " . $motivo;
            header("Location: cancelar_whatsapp.php?telefono=" . urlencode($reserva['telefono']) . "&tipo=" . urlencode($reserva['tipo_telefono']) . "&nombre=" . urlencode($reserva['NombreYApellido']) . "&mensaje=" . urlencode($mensaje_contacto));
        } else {
            header("Location: ../index.php?success=" . urlencode("Reserva cancelada correctamente. El usuario no tiene teléfono registrado."));
        }
        exit();
    } else if (!$esAdmin) {
        // Usuario canceló su propia reserva
        // Solo notificar a los administradores (sin flujo de WhatsApp)
        $mensaje_notif = $_SESSION['nombreyapellido'] . " ha cancelado su reserva de " . $reserva['info'] . " (" . date('d/m/Y', strtotime($reserva['fecha'])) . "). Motivo: " . $motivo;
        $stmt = $conexion->prepare("INSERT INTO notificaciones (id_usuario_origen, tipo, mensaje, para_admins, leido, fecha) VALUES (?, 'cancelacion', ?, 1, 0, NOW())");
        $stmt->bind_param("is", $usuario_id, $mensaje_notif);
        $stmt->execute();
        $stmt->close();

        header("Location: ../mis_reservas.php?success=" . urlencode("Reserva cancelada correctamente. Se notificó a los administradores."));
        exit();
    } else {
        // Admin canceló su propia reserva
        header("Location: ../index.php?success=" . urlencode("Reserva cancelada correctamente."));
        exit();
    }
}
?>
