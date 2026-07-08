<?php
session_start();
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: iniciar_sesion.php");
    exit();
}

include('include/conexion.php');
$usuario_id = $_SESSION['usuario_id'];
$esAdmin = isset($_SESSION['EsAdmin']) && $_SESSION['EsAdmin'] == true;
$loggedIn = true;

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    $nuevo_telefono = htmlspecialchars($_POST['telefono'], ENT_QUOTES, 'UTF-8');
    $nuevo_tipo = htmlspecialchars($_POST['tipo_telefono'], ENT_QUOTES, 'UTF-8');
    $nueva_foto = htmlspecialchars($_POST['foto_perfil'], ENT_QUOTES, 'UTF-8');
    
    $stmt = $conexion->prepare("UPDATE usuarios SET telefono = ?, tipo_telefono = ?, foto_perfil = ? WHERE ID = ?");
    $stmt->bind_param("sssi", $nuevo_telefono, $nuevo_tipo, $nueva_foto, $usuario_id);
    
    if ($stmt->execute()) {
        $_SESSION['telefono'] = $nuevo_telefono;
        $_SESSION['tipo_telefono'] = $nuevo_tipo;
        $_SESSION['foto_perfil'] = $nueva_foto;
        $mensaje_exito = "Perfil actualizado correctamente.";
    } else {
        $mensaje_error = "Error al actualizar el perfil.";
    }
    $stmt->close();
}

// Obtener datos actuales
$stmt = $conexion->prepare("SELECT usuario, NombreYApellido, telefono, tipo_telefono, foto_perfil FROM usuarios WHERE ID = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($usuario, $nombreyapellido, $telefono, $tipo_telefono, $foto_perfil);
$stmt->fetch();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Sistema de Reserva de Salones</title>
    <link rel="icon" href="https://i.imgur.com/fSjgaVI.jpeg" type="image/svg+xml">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                    },
                },
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                    display: ['Montserrat', 'sans-serif'],
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-gray-800 border-b border-gray-700 shadow-lg" x-data="{ isOpen: false }">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="flex items-center space-x-3">
                        <img src="img/logo.png" alt="Logo" class="h-12 w-12">
                        <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-white">Sistema de Reservas</h1>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="index.php" class="text-gray-300 hover:text-white font-medium px-4 py-2 rounded-lg transition-all flex items-center">
                        Inicio
                    </a>
                    <a href="mis_reservas.php" class="text-gray-300 hover:text-white font-medium px-4 py-2 rounded-lg transition-all flex items-center">
                        Mis Reservas
                    </a>
                    <a href="logout.php" class="text-white bg-red-600 hover:bg-red-700 font-medium px-4 py-2 rounded-lg transition-all">
                        Cerrar sesión
                    </a>
                </div>

                <div class="md:hidden">
                    <button type="button" class="text-gray-300 hover:text-white" @click="isOpen = !isOpen">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" x-show="isOpen" @click.away="isOpen = false" class="md:hidden" style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-700">
                <a href="index.php" class="block text-gray-300 hover:text-white px-3 py-2 rounded-md">Inicio</a>
                <a href="mis_reservas.php" class="block text-gray-300 hover:text-white px-3 py-2 rounded-md">Mis Reservas</a>
                <a href="logout.php" class="block text-white bg-red-600 hover:bg-red-700 font-medium px-3 py-2 rounded-md mt-2">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-12 flex justify-center">
        <div class="w-full max-w-xl">
            <div class="bg-gray-800 rounded-xl shadow-xl overflow-hidden border border-gray-700">
                <div class="px-6 py-8">
                    <div class="text-center mb-8">
                        <?php if (!empty($foto_perfil)): ?>
                            <img src="<?php echo htmlspecialchars($foto_perfil); ?>" alt="Foto de perfil" class="h-24 w-24 rounded-full mx-auto object-cover border-4 border-primary-600 mb-4 shadow-lg">
                        <?php else: ?>
                            <div class="h-24 w-24 rounded-full bg-primary-600 mx-auto flex items-center justify-center mb-4 shadow-lg">
                                <span class="text-4xl text-white font-bold"><?php echo substr(strtoupper($nombreyapellido), 0, 1); ?></span>
                            </div>
                        <?php endif; ?>
                        <h2 class="text-2xl font-bold text-white"><?php echo htmlspecialchars($nombreyapellido); ?></h2>
                        <p class="text-gray-400">@<?php echo htmlspecialchars($usuario); ?></p>
                    </div>

                    <?php if (isset($mensaje_exito)): ?>
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p><?php echo $mensaje_exito; ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($mensaje_error)): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p><?php echo $mensaje_error; ?></p>
                        </div>
                    <?php endif; ?>

                    <form action="perfil.php" method="POST" class="space-y-6">
                        <input type="hidden" name="accion" value="actualizar">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Nombre Completo</label>
                            <input type="text" disabled value="<?php echo htmlspecialchars($nombreyapellido); ?>" class="w-full bg-gray-700/50 border border-gray-600 text-gray-400 rounded-lg px-4 py-2 cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">Para cambiar tu nombre, contacta a un administrador.</p>
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-300 mb-1">Número de Teléfono</label>
                            <div class="flex">
                                <input type="text" id="telefono" name="telefono" required value="<?php echo htmlspecialchars($telefono ?? ''); ?>" class="w-full bg-gray-700 border border-gray-600 text-white rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all" placeholder="Ej: 2346 123456">
                                <select name="tipo_telefono" required class="bg-gray-700 border border-l-0 border-gray-600 text-white rounded-r-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all">
                                    <option value="whatsapp" <?php if($tipo_telefono == 'whatsapp') echo 'selected'; ?>>WhatsApp</option>
                                    <option value="celular_sin_wsp" <?php if($tipo_telefono == 'celular_sin_wsp') echo 'selected'; ?>>Sin WSP</option>
                                    <option value="fijo" <?php if($tipo_telefono == 'fijo') echo 'selected'; ?>>Línea Fija</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="foto_perfil" class="block text-sm font-medium text-gray-300 mb-1">Foto de Perfil (URL)</label>
                            <input type="url" id="foto_perfil" name="foto_perfil" value="<?php echo htmlspecialchars($foto_perfil ?? ''); ?>" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all" placeholder="https://ejemplo.com/mifoto.jpg">
                            <p class="text-xs text-gray-500 mt-1">Opcional. Pega la URL de una imagen para tu perfil.</p>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors flex justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 border-t border-gray-700 py-6 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> - Sistema de Reserva de Salones V2.1</p>
        </div>
    </footer>
</body>
</html>
