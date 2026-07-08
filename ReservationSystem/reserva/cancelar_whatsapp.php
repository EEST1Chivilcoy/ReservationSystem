<?php
if (!isset($_GET['telefono']) || !isset($_GET['mensaje'])) {
    header("Location: ../index.php");
    exit();
}

$telefono = htmlspecialchars($_GET['telefono']);
$mensaje = urlencode($_GET['mensaje']); // ya viene urlencoded pero por seguridad

$whatsapp_url = "https://wa.me/549" . ltrim($telefono, '0') . "?text=" . $_GET['mensaje']; // Asumiendo código país de arg (+549) o adaptarlo
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviando Notificación WhatsApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-xl shadow-xl max-w-md w-full text-center border border-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-16 w-16 mx-auto text-green-500 mb-4">
            <path d="M16.6 14c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.2-.6.8-.8 1-.1.2-.3.2-.5.1-.7-.3-1.4-.7-2-1.2-.5-.5-1-1.1-1.4-1.7-.1-.2 0-.4.1-.5.1-.1.2-.3.4-.4.1-.1.2-.3.2-.4.1-.2 0-.4 0-.5C10 9.5 9.4 8 9.1 7.4c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.4s1 2.8 1.1 3c.1.2 2 3.1 4.9 4.3.7.3 1.3.5 1.7.6.7.2 1.4.2 1.9.1.6-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2l-.4-.2m2.5-9.1C15.2 1 9 1 5.2 4.8 1.7 8.3 1 13.5 3 17.5L1.4 23l5.7-1.5C9 22.5 10.9 23 12.8 23c6.6 0 12-5.4 12-12 0-3.2-1.3-6.2-3.5-8.5-.2-.1-.5-.2-.7-.2h-.2c-.3 0-.7.3-1 .6z"/>
        </svg>
        <h2 class="text-2xl font-bold mb-4">Redirigiendo a WhatsApp...</h2>
        <p class="text-gray-400 mb-6">Si no eres redirigido automáticamente, haz clic en el botón de abajo.</p>
        <a href="<?php echo htmlspecialchars($whatsapp_url); ?>" target="_blank" id="wspLink" class="inline-block bg-green-500 hover:bg-green-600 text-white font-medium px-6 py-2 rounded-lg transition-colors w-full mb-4">Abrir WhatsApp</a>
        <a href="../index.php" class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-medium px-6 py-2 rounded-lg transition-colors w-full">Volver al Inicio</a>
    </div>

    <script>
        setTimeout(function() {
            document.getElementById('wspLink').click();
            setTimeout(function() {
                window.location.href = '../index.php?success=Reserva%20cancelada.%20Notificaci%C3%B3n%20abierta.';
            }, 500);
        }, 1500);
    </script>
</body>
</html>
