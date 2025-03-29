<?php
require '../include/VerificacionSesion.php';
$esAdmin = isset($_SESSION['EsAdmin']) && $_SESSION['EsAdmin'] == true;
?>

<!DOCTYPE html>
<html lang="es" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Reserva de Salones</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js para interacciones UI -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- jQuery y Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>

    <!-- Moment.js para manejo de fechas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Heroicons -->
    <script src="https://unpkg.com/heroicons@2.0.18/solid/index.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Montserrat', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        dark: {
                            800: '#1e1e2d',
                            850: '#151521',
                            900: '#0f0f17',
                            950: '#080810',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Estilos adicionales */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #1e1e2d;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #4338ca;
            border-radius: 20px;
        }
    </style>
</head>

<body class="bg-dark-900 text-gray-200 min-h-screen flex flex-col font-sans custom-scrollbar">
    <!-- Navbar rediseñado -->
    <nav class="bg-gray-800 border-b border-gray-700 shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="../index.php" class="flex items-center space-x-3">
                        <img src="../img/logo.png" alt="Logo" class="h-12 w-12">
                        <h1 class="text-xl md:text-2xl font-bold text-white font-display">Sistema de Reserva de Salones</h1>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="../index.php" class="text-white bg-gray-700 hover:bg-gray-600 font-medium px-4 py-2 rounded-lg transition-all flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" clip-rule="evenodd" />
                        </svg>
                        Inicio
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-300 hover:text-white focus:outline-none">
                        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Mobile menu -->
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg py-1 z-50">
                        <a href="../index.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700">Inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="flex-grow max-w-6xl w-full mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-primary-700 to-primary-900 rounded-lg shadow-lg p-6 mb-8">
            <h1 class="text-2xl md:text-3xl font-display font-bold text-white">Reservación de Salones</h1>
            <p class="text-primary-100 mt-2">Complete el formulario para solicitar un espacio educativo</p>
        </div>

        <!-- Tarjeta del Formulario -->
        <div class="bg-dark-850 rounded-xl shadow-lg border border-gray-800 overflow-hidden">
            <div class="p-6">
                <form action="cargar_sql.php" method="post" onsubmit="return validarFormulario()" x-data="{ 
          showAdminForm: false,
          showDivision: false,
          showOtroSalon: false,
          currentStep: 1,
          curso: 'Reunión',
          sitio: 'Salon de actos',
          updateDivision() {
            this.showDivision = this.curso !== 'Reunión';
          },
          updateOtroSalon() {
            this.showOtroSalon = this.sitio === 'Otro';
          }
        }">
                    <!-- Vista por pasos -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-white">Nueva Reserva</h2>
                            <div class="flex space-x-1">
                                <template x-for="step in 3" :key="step">
                                    <div class="flex items-center">
                                        <div
                                            :class="{
                        'bg-primary-600': currentStep >= step,
                        'bg-gray-700': currentStep < step
                      }"
                                            class="w-8 h-8 rounded-full flex items-center justify-center text-white font-medium transition-all duration-300">
                                            <span x-text="step"></span>
                                        </div>
                                        <div
                                            x-show="step < 3"
                                            :class="{
                        'bg-primary-600': currentStep > step,
                        'bg-gray-700': currentStep <= step
                      }"
                                            class="h-1 w-8">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Switch (Solo para administradores) -->
                    <?php if (isset($_SESSION['EsAdmin']) && $_SESSION['EsAdmin'] == true): ?>
                        <div class="mb-6 bg-dark-800 p-4 rounded-lg">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" x-model="showAdminForm">
                                <div class="relative w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                                <span class="ml-3 text-gray-300 font-medium">¿Es para otra persona?</span>
                            </label>

                            <div x-show="showAdminForm" x-transition class="mt-4 bg-dark-900 rounded-md p-4">
                                <label for="NombreYApellido" class="block text-sm font-medium text-gray-300 mb-1">Nombre y Apellido</label>
                                <input type="text" id="NombreYApellido" name="NombreYApellido" placeholder="Ingrese nombre completo"
                                    class="w-full bg-dark-800 border border-gray-700 text-white rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Paso 1: Información del Curso -->
                    <div x-show="currentStep === 1">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Selección de Curso -->
                                <div>
                                    <label for="curso" class="block text-sm font-medium text-gray-300 mb-1">Curso</label>
                                    <select id="curso" name="curso" x-model="curso" @change="updateDivision()"
                                        class="w-full bg-dark-800 border border-gray-700 text-white rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                        <option value="Reunión">Reunión</option>
                                        <option value="1º">1º</option>
                                        <option value="2º">2º</option>
                                        <option value="3º">3º</option>
                                        <option value="4º">4º</option>
                                        <option value="5º">5º</option>
                                        <option value="6º">6º</option>
                                        <option value="7º">7º</option>
                                    </select>
                                </div>

                                <!-- División (condicionalmente visible) -->
                                <div x-show="showDivision" x-transition>
                                    <label for="division" class="block text-sm font-medium text-gray-300 mb-1">División</label>
                                    <input type="text" id="division" name="division" placeholder="Ej: A, B, C..."
                                        class="w-full bg-dark-800 border border-gray-700 text-white rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            <!-- Materia -->
                            <div>
                                <label for="materia" class="block text-sm font-medium text-gray-300 mb-1">Materia</label>
                                <input type="text" id="materia" name="materia" placeholder="Ingrese el nombre de la materia"
                                    class="w-full bg-dark-800 border border-gray-700 text-white rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="button" @click="currentStep = 2"
                                class="px-5 py-2 bg-primary-600 text-white rounded-md font-medium hover:bg-primary-700 transition-all focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-dark-850">
                                Siguiente
                            </button>
                        </div>
                    </div>

                    <!-- Paso 2: Detalles de Tiempo -->
                    <div x-show="currentStep === 2" x-transition>
                        <div class="space-y-6">
                            <!-- Fecha -->
                            <div>
                                <label for="fecha" class="block text-sm font-medium text-gray-300 mb-1">Fecha de Reserva</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="date" id="fecha" name="fecha"
                                        class="w-full bg-dark-800 border border-gray-700 text-white rounded-md pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Hora de inicio -->
                                <div>
                                    <label for="horario" class="block text-sm font-medium text-gray-300 mb-1">Hora de Inicio</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="time" id="horario" name="horario" onchange="validarHorario()"
                                            class="w-full bg-dark-800 border border-gray-700 text-white rounded-md pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                    </div>
                                </div>

                                <!-- Hora de fin -->
                                <div>
                                    <label for="horario1" class="block text-sm font-medium text-gray-300 mb-1">Hora de Fin</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="time" id="horario1" name="horario1"
                                            class="w-full bg-dark-800 border border-gray-700 text-white rounded-md pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-between">
                            <button type="button" @click="currentStep = 1"
                                class="px-5 py-2 bg-gray-700 text-white rounded-md font-medium hover:bg-gray-600 transition-all focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-dark-850">
                                Anterior
                            </button>
                            <button type="button" @click="currentStep = 3"
                                class="px-5 py-2 bg-primary-600 text-white rounded-md font-medium hover:bg-primary-700 transition-all focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-dark-850">
                                Siguiente
                            </button>
                        </div>
                    </div>

                    <!-- Paso 3: Lugar y Materiales -->
                    <div x-show="currentStep === 3" x-transition>
                        <div class="space-y-6">
                            <!-- Selección de Sitio -->
                            <div>
                                <label for="info" class="block text-sm font-medium text-gray-300 mb-1">Sitio a Reservar</label>
                                <select id="info" name="info" x-model="sitio" @change="updateOtroSalon(); validarHorario()"
                                    class="w-full bg-dark-800 border border-gray-700 text-white rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                                    <option value="Salon de actos">Salón de actos</option>
                                    <option value="Comedor">Comedor</option>
                                    <option value="Audiovisuales">Audiovisuales</option>
                                    <option value="Otro">Otro (Especificar)</option>
                                </select>
                            </div>

                            <!-- Otro Salón (condicionalmente visible) -->
                            <div x-show="showOtroSalon" x-transition>
                                <label for="otro_salon" class="block text-sm font-medium text-gray-300 mb-1">Especificar otro salón</label>
                                <input type="text" id="otro_salon" name="otro_salon" placeholder="Describa el salón específico"
                                    class="w-full bg-dark-800 border border-gray-700 text-white rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                            </div>

                            <!-- Materiales -->
                            <div>
                                <label for="materiales" class="block text-sm font-medium text-gray-300 mb-1">Materiales necesarios</label>
                                <textarea id="materiales" name="materiales" rows="3" placeholder="Describa los materiales que necesitará de EMATP"
                                    class="w-full bg-dark-800 border border-gray-700 text-white rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"></textarea>
                            </div>
                        </div>

                        <div class="mt-8 space-y-4">
                            <button type="submit" name="boton" value="1"
                                class="w-full flex justify-center items-center px-5 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-md font-medium hover:from-green-700 hover:to-green-800 transition-all focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-dark-850">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Confirmar Reserva
                            </button>

                            <button type="submit" name="boton" value="0"
                                class="w-full flex justify-center items-center px-5 py-3 bg-gray-700 text-white rounded-md font-medium hover:bg-gray-600 transition-all focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-dark-850">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Anular Reserva
                            </button>

                            <div class="flex justify-start">
                                <button type="button" @click="currentStep = 2"
                                    class="px-5 py-2 bg-gray-700 text-white rounded-md font-medium hover:bg-gray-600 transition-all focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-dark-850">
                                    Anterior
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer rediseñado -->
    <footer class="bg-gray-800 border-t border-gray-700 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0 flex items-center">
                    <img src="https://i.imgur.com/fSjgaVI.jpeg" alt="Logo" class="h-10 w-10 mr-3 rounded-full border border-gray-600">
                    <div>
                        <span class="text-gray-200 font-medium">Sistema de Reserva de Salones</span>
                        <p class="text-gray-400 text-xs">Gestión eficiente de espacios educativos</p>
                    </div>
                </div>

                <div class="text-center md:text-right">
                    <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> - Sistema de Reserva de Salones V2</p>
                    <p class="text-gray-500 text-xs mt-1">Desarrollado por G. Erramuspe, Bernardo</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function validarFormulario() {
            const horarioValido = validarHorario();
            return horarioValido && validarHorarios();
        }

        function validarHorario() {
            const sitio = document.getElementById('info').value;
            const horario = document.getElementById('horario').value;

            if (horario) {
                const hora = horario.split(':')[0];
                const horaMinutos = parseInt(hora);

                if (sitio === 'Comedor') {
                    if (horaMinutos < 14) {
                        alert('Si el sitio es Comedor, el horario debe ser posterior a las 2 PM.');
                        document.getElementById('horario').value = '';
                        return false;
                    }
                }
            }
            return true;
        }

        function validarHorarios() {
            const horaInicio = document.getElementById('horario').value;
            const horaFin = document.getElementById('horario1').value;

            if (horaInicio && horaFin) {
                const [horaInicioH, horaInicioM] = horaInicio.split(':').map(Number);
                const [horaFinH, horaFinM] = horaFin.split(':').map(Number);

                if (horaFinH < horaInicioH || (horaFinH === horaInicioH && horaFinM < horaInicioM)) {
                    alert('La hora de fin no puede ser anterior a la hora de inicio.');
                    return false;
                }
            }
            return true;
        }

        // Inicialización al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Alpine.js se encargará de la inicialización
        });
    </script>
</body>

</html>