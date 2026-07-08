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

    // Obtener info de la reserva
    $stmt = $conexion->prepare("SELECT r.*, u.telefono, u.tipo_telefono, u.NombreYApellido FROM tabla r LEFT JOIN usuarios u ON r.id_usuario = u.ID WHERE r.ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reserva = $result->fetch_assoc();
    $stmt->close();

    if (!$reserva) {
        header("Location: ../mis_reservas.php?error=Reserva no encontrada");
        exit();
    }

    // Si no es admin y no es dueño de la reserva
    if (!$esAdmin && $reserva['id_usuario'] != $usuario_id) {
        header("Location: ../mis_reservas.php?error=No tienes permiso para cancelar esta reserva");
        exit();
    }

    // Verificar que no sea pasada
    $hoy = date('Y-m-d');
    if ($reserva['fecha'] < $hoy) {
        header("Location: ../mis_reservas.php?error=No se puede cancelar una reserva que ya pasó");
        exit();
    }

    $id_reserva_original = $reserva['ID'];
    $id_usuario_reserva = $reserva['id_usuario'] ? $reserva['id_usuario'] : 0;
    
    // Guardar en reservas_canceladas
    $stmt = $conexion->prepare("INSERT INTO reservas_canceladas (id_usuario_origen, id_cancelador, info_reserva, motivo, fecha_cancelacion) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiss", $id_usuario_reserva, $usuario_id, $reserva['info'], $motivo);
    $stmt->execute();
    $stmt->close();

    // Eliminar de tabla
    $stmt = $conexion->prepare("DELETE FROM tabla WHERE ID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Notificaciones
    if ($esAdmin && $reserva['id_usuario'] != $usuario_id) {
        // Admin canceló la reserva de un usuario.
        // Aquí podríamos generar el enlace de WhatsApp y pasar a otra pantalla, o dejarlo así por ahora.
        $tel = $reserva['telefono'];
        $tipo = $reserva['tipo_telefono'];
        if ($tel) {
            $mensaje = "Hola " . $reserva['NombreYApellido'] . ", tu reserva de " . $reserva['info'] . " para el " . $reserva['fecha'] . " ha sido cancelada por el siguiente motivo: " . $motivo;
            if ($tipo == 'whatsapp') {
                $tel_wsp = preg_replace('/[^0-9]/', '', $tel);
                // Redirigir a una página que lance el WhatsApp
                header("Location: cancelar_whatsapp.php?telefono=$tel_wsp&mensaje=" . urlencode($mensaje));
                exit();
            } else {
                header("Location: ../index.php?success=" . urlencode("Reserva cancelada. Recuerde contactar al usuario al teléfono: $tel ($tipo). Motivo: $motivo"));
                exit();
            }
        }
        header("Location: ../index.php?success=" . urlencode("Reserva cancelada correctamente."));
        exit();
    } else if (!$esAdmin) {
        // Usuario canceló su reserva, notificar a admins (id_usuario = 0 como destino general o crear registro con tipo_notificacion)
        // Para simplificar, insertamos la notificación para admins (id_usuario_origen = $usuario_id, leido = 0)
        $mensaje_notif = "ha cancelado su reserva de " . $reserva['info'] . " (" . date('d/m/Y', strtotime($reserva['fecha'])) . "). Motivo: " . $motivo;
        $stmt = $conexion->prepare("INSERT INTO notificaciones (id_usuario_origen, tipo, mensaje, leido, fecha) VALUES (?, 'cancelacion', ?, 0, NOW())");
        $stmt->bind_param("is", $usuario_id, $mensaje_notif);
        $stmt->execute();
        $stmt->close();
        
        header("Location: ../mis_reservas.php?success=" . urlencode("Reserva cancelada correctamente."));
        exit();
    } else {
        header("Location: ../index.php?success=" . urlencode("Reserva cancelada correctamente."));
        exit();
    }
}
?>
