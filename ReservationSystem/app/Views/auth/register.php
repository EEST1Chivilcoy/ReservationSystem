<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse | Sistema de Reserva de Salones</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#3B82F6', dark: '#2563EB' },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Montserrat', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col">

    <nav class="bg-gray-800 border-b border-gray-700 shadow-lg">
        <div class="container mx-auto px-4 py-3">
             <div class="flex items-center space-x-3">
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
                <div class="bg-gray-700 px-6 py-4 border-b border-gray-600">
                    <h2 class="text-xl font-semibold text-white text-center">Crear nueva cuenta</h2>
                </div>

                <form action="/register" method="POST" class="px-6 py-4 space-y-6">
                    <div class="space-y-1">
                        <label for="usuario" class="block text-sm font-medium text-gray-300">Nombre de usuario</label>
                        <input type="text" id="usuario" name="usuario" required class="bg-gray-700 border border-gray-600 text-white block w-full px-4 py-2 rounded-lg focus:ring-primary-500 focus:border-primary-500" placeholder="Usuario">
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-sm font-medium text-gray-300">Contraseña</label>
                        <input type="password" id="password" name="clave" required class="bg-gray-700 border border-gray-600 text-white block w-full px-4 py-2 rounded-lg focus:ring-primary-500 focus:border-primary-500" placeholder="Contraseña">
                    </div>

                    <div class="space-y-1">
                        <label for="nomyapp" class="block text-sm font-medium text-gray-300">Nombre y Apellido</label>
                        <input type="text" id="nomyapp" name="nomyapp" required class="bg-gray-700 border border-gray-600 text-white block w-full px-4 py-2 rounded-lg focus:ring-primary-500 focus:border-primary-500" placeholder="Nombre completo">
                    </div>

                    <div>
                        <button type="submit" class="w-full py-2 px-4 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition duration-200">
                            Crear cuenta
                        </button>
                    </div>
                </form>

                <div class="px-6 py-4 bg-gray-700 border-t border-gray-600 text-center">
                    <p class="text-sm text-gray-300">
                        ¿Ya tienes una cuenta? <a href="/login" class="font-medium text-primary-400 hover:text-primary-300">Iniciar sesión</a>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 border-t border-gray-700 py-6 text-center">
        <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> - Sistema de Reserva de Salones V2</p>
    </footer>
</body>
</html>
