<?php
if (!isset($_GET['telefono']) || !isset($_GET['mensaje'])) {
    header("Location: ../index.php");
    exit();
}

$telefono = htmlspecialchars($_GET['telefono']);
$mensaje = htmlspecialchars($_GET['mensaje']);
$tipo = isset($_GET['tipo']) ? htmlspecialchars($_GET['tipo']) : 'whatsapp';
$nombre = isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : 'Usuario';

// Generar URL de wa.me. Si viene con 0 a la izquierda se lo quitamos, asumimos código país
$tel_wsp = ltrim($telefono, '0');
$whatsapp_url = "https://wa.me/549" . $tel_wsp . "?text=" . urlencode($mensaje);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviso de Cancelación</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-4">
    <div class="bg-gray-800 p-8 rounded-2xl shadow-2xl max-w-lg w-full border border-gray-700">
        
        <?php if ($tipo === 'whatsapp'): ?>
            <!-- Caso con WhatsApp: Redirección automática -->
            <div class="text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-900/30 text-green-400 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="h-10 w-10"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.458L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.965C16.488 2.01 14.07 1.01 11.478 1.01 6.046 1.01 1.62 5.378 1.617 10.806c-.001 1.69.443 3.336 1.286 4.795l-.997 3.64 3.74-.979zm11.233-5.662c-.271-.136-1.602-.79-1.85-.88-.248-.09-.429-.136-.61.136-.18.271-.698.88-.857 1.06-.158.18-.317.203-.588.068-1.52-.75-2.614-1.3-3.666-3.11-.278-.477.278-.443.796-1.472.083-.169.041-.317-.021-.452-.061-.136-.61-1.472-.836-2.014-.22-.53-.443-.457-.61-.466-.157-.008-.339-.009-.52-.009-.18 0-.475.068-.723.339-.248.271-.95.928-.95 2.262 0 1.333.972 2.621 1.107 2.802.136.18 1.913 2.922 4.633 4.097.647.28 1.153.447 1.547.572.65.207 1.243.177 1.711.107.521-.078 1.602-.656 1.829-1.289.227-.633.227-1.176.158-1.289-.068-.113-.248-.18-.52-.317z"/></svg>
                </div>
                <h2 class="text-2xl font-bold mb-2">Abriendo WhatsApp Web...</h2>
                <p class="text-gray-400 mb-6">Estamos generando el mensaje formal de cancelación para enviarlo a <strong><?php echo $nombre; ?></strong>.</p>
                
                <div class="w-full bg-gray-700 h-1.5 rounded-full mb-6 overflow-hidden">
                    <div class="bg-green-500 h-full rounded-full animate-[pulse_1s_infinite] w-full"></div>
                </div>

                <a href="<?php echo $whatsapp_url; ?>" target="_blank" id="wspLink" class="inline-flex justify-center items-center bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl transition-all w-full mb-4 shadow-lg shadow-green-900/20">
                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.458L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.965C16.488 2.01 14.07 1.01 11.478 1.01 6.046 1.01 1.62 5.378 1.617 10.806c-.001 1.69.443 3.336 1.286 4.795l-.997 3.64 3.74-.979zm11.233-5.662c-.271-.136-1.602-.79-1.85-.88-.248-.09-.429-.136-.61.136-.18.271-.698.88-.857 1.06-.158.18-.317.203-.588.068-1.52-.75-2.614-1.3-3.666-3.11-.278-.477.278-.443.796-1.472.083-.169.041-.317-.021-.452-.061-.136-.61-1.472-.836-2.014-.22-.53-.443-.457-.61-.466-.157-.008-.339-.009-.52-.009-.18 0-.475.068-.723.339-.248.271-.95.928-.95 2.262 0 1.333.972 2.621 1.107 2.802.136.18 1.913 2.922 4.633 4.097.647.28 1.153.447 1.547.572.65.207 1.243.177 1.711.107.521-.078 1.602-.656 1.829-1.289.227-.633.227-1.176.158-1.289-.068-.113-.248-.18-.52-.317z"/></svg>
                    Abrir WhatsApp Web Manual
                </a>
            </div>

            <script>
                setTimeout(function() {
                    const wspWindow = window.open('<?php echo $whatsapp_url; ?>', '_blank');
                    // Si el navegador bloqueó la ventana emergente, abrimos en la misma pestaña
                    if (!wspWindow || wspWindow.closed || typeof wspWindow.closed == 'undefined') {
                        window.location.href = '<?php echo $whatsapp_url; ?>';
                    } else {
                        setTimeout(function() {
                            window.location.href = '../index.php?success=Reserva%20cancelada%20y%20notificaci%C3%B3n%20enviada.';
                        }, 1000);
                    }
                }, 1200);
            </script>

        <?php else: ?>
            <!-- Caso sin WhatsApp (Celular sin wsp o Fijo): Llamada necesaria -->
            <div>
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-900/30 text-amber-400 mb-6">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                
                <h2 class="text-2xl font-bold text-center mb-2">⚠️ Llamada Telefónica Requerida</h2>
                <p class="text-gray-400 text-center mb-6">El usuario no utiliza WhatsApp. Por favor, comunícate telefónicamente para avisarle de la cancelación.</p>
                
                <div class="bg-gray-900/80 rounded-xl p-4 mb-6 border border-gray-700 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-500 uppercase font-semibold">Número de Teléfono</span>
                        <p class="text-2xl font-mono text-amber-300"><?php echo $telefono; ?></p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-500 uppercase font-semibold">Tipo</span>
                        <p class="text-sm font-medium text-white"><?php echo $tipo === 'celular_sin_wsp' ? 'Celular (Sin WSP)' : 'Línea Fija'; ?></p>
                    </div>
                </div>

                <div class="mb-6">
                    <span class="text-xs text-gray-500 uppercase font-semibold block mb-2">Mensaje formal sugerido para la llamada:</span>
                    <div class="bg-gray-900/50 rounded-xl p-4 text-sm text-gray-300 border border-gray-700 leading-relaxed max-h-48 overflow-y-auto whitespace-pre-wrap"><?php echo htmlspecialchars($mensaje); ?></div>
                </div>

                <a href="../index.php?success=Reserva%20cancelada.%20Por%20favor%20recuerde%20avisar%20al%20usuario." class="inline-flex justify-center items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition-all w-full shadow-lg shadow-blue-900/20">
                    Entendido, Volver al Inicio
                </a>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>
