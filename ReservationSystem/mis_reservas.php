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

// Consultar reservas del usuario
$consulta = "SELECT * FROM tabla WHERE id_usuario = ? ORDER BY fecha DESC, horario DESC";
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$reservas = $resultado->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Consultar reservas canceladas del usuario
$consulta_canceladas = "SELECT * FROM reservas_canceladas WHERE id_usuario_origen = ? ORDER BY fecha_cancelacion DESC";
$stmt_cancel = $conexion->prepare($consulta_canceladas);
$stmt_cancel->bind_param("i", $usuario_id);
$stmt_cancel->execute();
$resultado_canceladas = $stmt_cancel->get_result();
$reservas_canceladas = $resultado_canceladas->fetch_all(MYSQLI_ASSOC);
$stmt_cancel->close();

mysqli_close($conexion);

$reservas_proximas = [];
$reservas_pasadas = [];
$hoy = date('Y-m-d');
foreach ($reservas as $reserva) {
    if ($reserva['fecha'] < $hoy) {
        $reservas_pasadas[] = $reserva;
    } else {
        $reservas_proximas[] = $reserva;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - Sistema de Reserva de Salones</title>

    <link rel="icon" href="https://i.imgur.com/fSjgaVI.jpeg" type="image/svg+xml">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js for UI interactions -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom Tailwind config -->
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
        .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    </style>
</head>

<body class="bg-gray-900 text-gray-200 min-h-screen flex flex-col" x-data="{
    showErrorModal: <?php echo isset($_GET['error']) ? 'true' : 'false'; ?>,
    showSuccessModal: <?php echo isset($_GET['success']) ? 'true' : 'false'; ?>,
    errorMessage: '<?php echo isset($_GET['error']) ? htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') : ''; ?>',
    successMessage: '<?php echo isset($_GET['success']) ? htmlspecialchars($_GET['success'], ENT_QUOTES, 'UTF-8') : ''; ?>',
    showCancelModal: false,
    reservaId: null,
    reservaInfo: ''
}">

    <!-- Alertas -->
    <div x-show="showErrorModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center" style="display: none;">
        <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" @click="showErrorModal = false"></div>
        <div class="relative bg-gray-800 rounded-lg max-w-md w-full mx-4 shadow-xl border border-gray-700">
            <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-lg">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">Error</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-300" x-text="errorMessage"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg border-t border-gray-600">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm" @click="showErrorModal = false">Entendido</button>
            </div>
        </div>
    </div>

    <div x-show="showSuccessModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center" style="display: none;">
        <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" @click="showSuccessModal = false"></div>
        <div class="relative bg-gray-800 rounded-lg max-w-md w-full mx-4 shadow-xl border border-gray-700">
            <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-lg">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-white">Éxito</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-300" x-text="successMessage"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg border-t border-gray-600">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm" @click="showSuccessModal = false">Entendido</button>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="bg-gray-800 border-b border-gray-700 shadow-lg" x-data="{ isOpen: false }">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="flex items-center space-x-3">
                        <img src="img/logo.png" alt="Logo" class="h-12 w-12">
                        <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-white">Sistema de Reserva de Salones</h1>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="perfil.php" class="text-gray-300 hover:text-white font-medium px-2 py-2 rounded-lg transition-all flex items-center" title="Mi Perfil">
                        <?php if (isset($_SESSION['foto_perfil']) && !empty($_SESSION['foto_perfil'])): ?>
                            <img src="<?php echo htmlspecialchars($_SESSION['foto_perfil']); ?>" alt="Perfil" class="h-8 w-8 rounded-full object-cover border border-gray-600">
                        <?php else: ?>
                            <div class="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center text-white text-sm font-bold">
                                <?php echo substr(strtoupper($_SESSION['nombreyapellido']), 0, 1); ?>
                            </div>
                        <?php endif; ?>
                    </a>
                    <a href="index.php" class="text-gray-300 hover:text-white font-medium px-4 py-2 rounded-lg transition-all flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        Inicio
                    </a>
                    <a href="reserva/cargar_reserva.php?tabla=" class="text-white bg-blue-600 hover:bg-blue-700 font-medium px-4 py-2 rounded-lg transition-all flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" /></svg>
                        Reservar
                    </a>
                    <a href="logout.php" class="text-white bg-red-600 hover:bg-red-700 font-medium px-4 py-2 rounded-lg transition-all flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
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
                <a href="index.php" class="block text-gray-300 hover:text-white font-medium px-3 py-2 rounded-md text-center">Inicio</a>
                <a href="reserva/cargar_reserva.php?tabla=" class="block text-white bg-blue-600 hover:bg-blue-700 font-medium px-3 py-2 rounded-md text-center mt-2">Reservar</a>
                <a href="logout.php" class="block text-white bg-red-600 hover:bg-red-700 font-medium px-3 py-2 rounded-md text-center mt-2">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">
        <section class="mb-8 flex justify-between items-center flex-wrap gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">Mi Historial de Reservas</h2>
                <p class="text-gray-400">Aquí puedes ver y gestionar las reservas que has realizado.</p>
            </div>
            <a href="reserva/cargar_reserva.php?tabla=" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                Nueva Reserva
            </a>
        </section>

        <?php if (empty($reservas_proximas) && empty($reservas_pasadas) && empty($reservas_canceladas)): ?>
            <div class="bg-gray-800 rounded-xl p-8 text-center border border-gray-700 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <h3 class="text-xl font-medium text-gray-300 mb-2">No tienes reservas registradas</h3>
                <p class="text-gray-500 mb-6">Parece que aún no has hecho ninguna reserva. ¡Anímate a reservar tu primer salón!</p>
                <a href="reserva/cargar_reserva.php?tabla=" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-6 rounded-lg shadow transition-colors">
                    Hacer mi primera reserva
                </a>
            </div>
        <?php else: ?>
            <!-- Sección: Reservas Próximas -->
            <section class="mb-12">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center border-b border-gray-700 pb-2">
                    <span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                    Próximas Reservas (Vigentes)
                </h3>
                <?php if (empty($reservas_proximas)): ?>
                    <div class="bg-gray-800/40 rounded-xl p-6 text-center border border-gray-700/50">
                        <p class="text-gray-400 text-sm">No tienes reservas próximas agendadas.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($reservas_proximas as $reserva): 
                            $fecha_reserva = $reserva['fecha'];
                            $fecha_obj = new DateTime($fecha_reserva);
                            $fecha_formateada = $fecha_obj->format('d/m/Y');
                            $hora_inicio = substr($reserva['horario'], 0, 5);
                            $hora_fin = substr($reserva['horario1'], 0, 5);
                        ?>
                            <div class="bg-gray-800 rounded-xl shadow-lg border border-gray-700 overflow-hidden card-hover flex flex-col h-full relative">
                                <div class="absolute top-4 right-4 bg-green-500/20 text-green-400 border border-green-500/30 text-xs font-bold px-2 py-1 rounded shadow">
                                    Próxima
                                </div>
                                
                                <div class="p-6 flex-grow">
                                    <div class="flex items-center mb-4 border-b border-gray-700 pb-4">
                                        <div class="bg-blue-900/40 p-3 rounded-lg mr-4 text-blue-400 border border-blue-500/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-white"><?php echo htmlspecialchars($reserva['info']); ?></h3>
                                            <p class="text-sm text-gray-400"><?php echo $fecha_formateada; ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3 mb-4">
                                        <div class="flex justify-between">
                                            <span class="text-gray-400 text-sm">Horario:</span>
                                            <span class="text-gray-200 font-medium"><?php echo $hora_inicio . ' - ' . $hora_fin; ?></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-400 text-sm">Curso/Evento:</span>
                                            <span class="text-gray-200 font-medium text-right"><?php echo htmlspecialchars($reserva['curso']); ?></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-400 text-sm">Materia/Motivo:</span>
                                            <span class="text-gray-200 font-medium text-right"><?php echo htmlspecialchars($reserva['materia']); ?></span>
                                        </div>
                                        <?php if (!empty($reserva['materiales'])): ?>
                                        <div class="flex justify-between">
                                            <span class="text-gray-400 text-sm">Materiales:</span>
                                            <span class="text-gray-200 font-medium text-right truncate w-1/2" title="<?php echo htmlspecialchars($reserva['materiales']); ?>">
                                                <?php echo htmlspecialchars($reserva['materiales']); ?>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-750 px-6 py-4 border-t border-gray-700 mt-auto grid grid-cols-1 gap-2">
                                    <button @click="reservaId = <?php echo $reserva['ID']; ?>; reservaInfo = '<?php echo htmlspecialchars(addslashes($reserva['info'])); ?>'; showCancelModal = true" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-transparent border border-red-500 text-red-500 hover:bg-red-500 hover:text-white rounded-lg transition-colors font-medium text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Cancelar Reserva
                                    </button>
                                
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Sección: Historial de Reservas Pasadas -->
            <section class="mt-12">
                <h3 class="text-xl font-bold text-gray-400 mb-6 flex items-center border-b border-gray-800 pb-2">
                    <span class="inline-block w-3 h-3 bg-gray-600 rounded-full mr-2"></span>
                    Historial de Reservas Pasadas (Finalizadas)
                </h3>
                <?php if (empty($reservas_pasadas)): ?>
                    <div class="bg-gray-800/20 rounded-xl p-6 text-center border border-gray-700/30">
                        <p class="text-gray-500 text-sm">No tienes reservas pasadas finalizadas.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($reservas_pasadas as $reserva): 
                            $fecha_reserva = $reserva['fecha'];
                            $fecha_obj = new DateTime($fecha_reserva);
                            $fecha_formateada = $fecha_obj->format('d/m/Y');
                            $hora_inicio = substr($reserva['horario'], 0, 5);
                            $hora_fin = substr($reserva['horario1'], 0, 5);
                        ?>
                            <div class="bg-gray-800/40 rounded-xl border border-gray-700/40 overflow-hidden flex flex-col h-full relative opacity-60">
                                <div class="absolute top-4 right-4 bg-gray-700/50 text-gray-400 border border-gray-700/30 text-xs font-semibold px-2 py-1 rounded">
                                    Finalizada
                                </div>
                                
                                <div class="p-6 flex-grow">
                                    <div class="flex items-center mb-4 border-b border-gray-700/30 pb-4">
                                        <div class="bg-gray-700/30 p-3 rounded-lg mr-4 text-gray-500 border border-gray-700/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-300"><?php echo htmlspecialchars($reserva['info']); ?></h3>
                                            <p class="text-sm text-gray-500"><?php echo $fecha_formateada; ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3 text-sm text-gray-400">
                                        <div class="flex justify-between">
                                            <span>Horario:</span>
                                            <span class="text-gray-300 font-medium"><?php echo $hora_inicio . ' - ' . $hora_fin; ?></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Curso/Evento:</span>
                                            <span class="text-gray-300 font-medium text-right"><?php echo htmlspecialchars($reserva['curso']); ?></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Materia/Motivo:</span>
                                            <span class="text-gray-300 font-medium text-right"><?php echo htmlspecialchars($reserva['materia']); ?></span>
                                        </div>
                                        <?php if (!empty($reserva['materiales'])): ?>
                                        <div class="flex justify-between">
                                            <span>Materiales:</span>
                                            <span class="text-gray-300 font-medium text-right truncate w-1/2" title="<?php echo htmlspecialchars($reserva['materiales']); ?>">
                                                <?php echo htmlspecialchars($reserva['materiales']); ?>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Sección: Reservas Canceladas -->
            <section class="mt-12">
                <h3 class="text-xl font-bold text-red-400 mb-6 flex items-center border-b border-gray-800 pb-2">
                    <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-2 animate-pulse"></span>
                    Historial de Reservas Canceladas
                </h3>
                <?php if (empty($reservas_canceladas)): ?>
                    <div class="bg-gray-800/20 rounded-xl p-6 text-center border border-gray-700/30">
                        <p class="text-gray-500 text-sm">No tienes reservas canceladas.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($reservas_canceladas as $cancelada): 
                            $fecha_c = new DateTime($cancelada['fecha_cancelacion']);
                            $fecha_cancelacion_formateada = $fecha_c->format('d/m/Y H:i');
                        ?>
                            <div class="bg-red-950/20 rounded-xl border border-red-500/20 overflow-hidden flex flex-col h-full relative opacity-75">
                                <div class="absolute top-4 right-4 bg-red-600/20 text-red-400 border border-red-500/30 text-xs font-semibold px-2 py-1 rounded">
                                    Cancelada
                                </div>
                                
                                <div class="p-6 flex-grow">
                                    <div class="flex items-center mb-4 border-b border-red-500/20 pb-4">
                                        <div class="bg-red-900/40 p-3 rounded-lg mr-4 text-red-400 border border-red-500/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-red-300"><?php echo htmlspecialchars($cancelada['info_reserva']); ?></h3>
                                            <p class="text-sm text-gray-500">Cancelada el <?php echo $fecha_cancelacion_formateada; ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3 text-sm text-gray-400">
                                        <div class="bg-red-950/30 p-3 rounded-lg border border-red-900/20">
                                            <span class="text-red-400 font-semibold block mb-1">Motivo de la cancelación:</span>
                                            <span class="text-gray-300 leading-relaxed"><?php echo htmlspecialchars($cancelada['motivo']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 border-t border-gray-700 py-6 mt-10">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0 flex items-center">
                    <img src="https://i.imgur.com/fSjgaVI.jpeg" alt="Logo" class="h-10 w-10 mr-3 rounded-full border border-gray-600">
                    <div>
                        <span class="text-gray-200 font-medium">Sistema de Reserva de Salones</span>
                        <p class="text-gray-400 text-xs">Gestión eficiente de espacios educativos</p>
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> - Sistema de Reserva de Salones V2.1</p>
                    <p class="text-gray-500 text-xs mt-1">Desarrollado por Bernardo G. Erramuspe, estudiante de 7mo año en 2024</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal Cancelar -->
    <div x-show="showCancelModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center" style="display: none;">
        <div class="fixed inset-0 bg-black bg-opacity-80 transition-opacity" @click="showCancelModal = false"></div>
        <div class="relative bg-gray-800 rounded-xl max-w-md w-full mx-4 shadow-2xl border border-gray-700">
            <div class="px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Cancelar Reserva</h3>
                <button @click="showCancelModal = false" class="text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form action="reserva/cancelar_reserva_sql.php" method="POST" class="p-6">
                <input type="hidden" name="id" x-model="reservaId">
                <input type="hidden" name="info" x-model="reservaInfo">
                
                <p class="text-gray-300 text-sm mb-4">Vas a cancelar la reserva <strong x-text="reservaInfo" class="text-white"></strong>. Por favor, indica el motivo, esto notificará a los administradores.</p>
                
                <label class="block text-sm font-medium text-gray-400 mb-2">Motivo de cancelación</label>
                <textarea name="motivo" required rows="3" class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-3 py-2 mb-4 focus:ring-red-500 focus:border-red-500" placeholder="Ej: Me equivoqué de horario, ya no necesito el salón..."></textarea>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="showCancelModal = false" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">Atrás</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">Confirmar Cancelación</button>
                </div>
            </form>
        </div>
    </div>


</body>
</html>
