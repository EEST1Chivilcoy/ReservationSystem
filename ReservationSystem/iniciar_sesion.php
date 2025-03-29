<!DOCTYPE html>
<html lang="es" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Sistema de Reserva de Salones</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#3B82F6',
                            dark: '#2563EB'
                        },
                        secondary: {
                            DEFAULT: '#10B981',
                            dark: '#059669'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Montserrat', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Alpine.js para interactividad -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">
    <!-- Navbar rediseñado -->
    <nav class="bg-gray-800 border-b border-gray-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="img/logo.png" alt="Logo" class="h-10 w-10 rounded-full border-2 border-blue-500">
                        <span class="ml-3 text-xl font-bold font-display tracking-wider text-blue-400">SISTEMA DE RESERVAS</span>
                    </div>
                </div>

                <!-- Menú de navegación para escritorio -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="index.php" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out hover:bg-gray-700">
                        Inicio
                    </a>
                    <a href="reservas.php" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out hover:bg-gray-700">
                        Ver Reservas
                    </a>
                </div>

                <!-- Menú móvil (hamburguesa) -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="text-gray-400 hover:text-white focus:outline-none" x-data="{ open: false }" @click="open = !open">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido principal rediseñado -->
    <main class="flex-grow flex items-center justify-center p-4 sm:p-6 md:p-8">
        <div class="w-full max-w-md">
            <div class="bg-gray-800 border border-gray-700 shadow-xl rounded-xl overflow-hidden backdrop-blur-lg">
                <!-- Encabezado del formulario -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 rounded-t-xl">
                    <div class="flex justify-center">
                        <div class="bg-white/10 backdrop-blur-sm p-3 rounded-full border border-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="mt-4 text-center text-2xl font-bold font-display text-white">Iniciar Sesión</h2>
                    <p class="mt-1 text-center text-gray-200 text-sm">Accede a tu cuenta para gestionar reservas</p>
                </div>

                <!-- Formulario -->
                <div class="p-6 space-y-5">
                    <form action="login.php" method="POST">
                        <div class="space-y-4">
                            <!-- Campo Usuario -->
                            <div>
                                <label for="usuario" class="block text-sm font-medium text-gray-300 mb-1">Usuario</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="usuario" name="username" required
                                        class="pl-10 w-full py-2 px-4 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-100 placeholder-gray-400 transition-all duration-200"
                                        placeholder="Ingresa tu nombre de usuario">
                                </div>
                            </div>

                            <!-- Campo Contraseña -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Contraseña</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input type="password" id="password" name="password" required
                                        class="pl-10 w-full py-2 px-4 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-gray-100 placeholder-gray-400 transition-all duration-200"
                                        placeholder="Ingresa tu contraseña">
                                </div>
                            </div>

                            <!-- Botón de inicio de sesión -->
                            <div class="pt-2">
                                <button type="submit"
                                    class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-500 font-medium transition duration-150 ease-in-out">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    Iniciar sesión
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Enlace para registrarse -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-400">
                            ¿Aún no tienes una cuenta?
                            <a href="registrarse.php" class="font-medium text-blue-400 hover:text-blue-300 hover:underline transition duration-150 ease-in-out">
                                Regístrate ahora
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Ayuda adicional -->
            <div class="mt-6 text-center text-sm text-gray-400">
                <p>¿Necesitas ayuda? <a href="#" class="text-blue-400 hover:text-blue-300">Contacta a soporte</a></p>
            </div>
        </div>
    </main>

    <!-- Footer rediseñado -->
    <footer class="bg-gray-800 border-t border-gray-700 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0 flex items-center">
                    <img src="img/logo.png" alt="Logo" class="h-10 w-10 mr-3 rounded-full border border-gray-600">
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
</body>

</html>