<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Sistema de Reserva de Salones</title>
    <!-- Tailwind CSS (CDN por ahora, como en el original) -->
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <!-- Simple Navbar -->
    <nav class="bg-gray-800 border-b border-gray-700 shadow-lg">
        <div class="container mx-auto px-4 py-3">
             <div class="flex items-center space-x-3">
                <!-- Ajustar ruta de imagen si es necesario -->
                <img src="/assets/img/logo.png" alt="Logo" class="h-12 w-12">
                <h1 class="text-xl md:text-2xl font-bold text-white font-display">Sistema de Reserva de Salones</h1>
            </div>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center p-4 sm:p-6 md:p-8">
        <div class="w-full max-w-md">
            
            <?php if(isset($error)): ?>
                <div class="bg-red-500 text-white p-3 rounded mb-4 text-center">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="bg-gray-800 border border-gray-700 shadow-xl rounded-xl overflow-hidden backdrop-blur-lg">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 rounded-t-xl text-center">
                   <h2 class="text-2xl font-bold font-display text-white">Iniciar Sesión</h2>
                </div>

                <div class="p-6 space-y-5">
                    <form action="/login" method="POST">
                        <div class="space-y-4">
                            <div>
                                <label for="usuario" class="block text-sm font-medium text-gray-300 mb-1">Usuario</label>
                                <input type="text" id="usuario" name="username" required class="w-full py-2 px-4 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Usuario">
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Contraseña</label>
                                <input type="password" id="password" name="password" required class="w-full py-2 px-4 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Contraseña">
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full py-2.5 px-4 rounded-lg text-white bg-blue-600 hover:bg-blue-700 font-medium transition duration-150">
                                    Iniciar sesión
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-400">
                             ¿Aún no tienes una cuenta? <a href="/register" class="font-medium text-blue-400 hover:text-blue-300">Regístrate ahora</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 border-t border-gray-700 py-6 text-center">
        <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> - Sistema de Reserva de Salones V2</p>
    </footer>
</body>
</html>
