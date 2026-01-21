<!DOCTYPE html>
<html lang="es" class="<?php echo $isChristmasWeek ? 'navidad' : ''; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Salones</title>
    <link rel="icon" href="/assets/img/logo.png" type="image/svg+xml">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/es.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#0ea5e9', dark: '#0369a1' },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Montserrat', sans-serif; }
        .fc { 
            --fc-page-bg-color: #121826; 
            --fc-neutral-bg-color: #1F2937;
            --fc-list-event-hover-bg-color: #374151;
            --fc-button-text-color: #F9FAFB;
            --fc-button-bg-color: #2563EB;
            --fc-button-border-color: #2563EB;
            --fc-event-text-color: #F9FAFB;
        }
        .fc-theme-standard th { background-color: #1F2937; color: #F9FAFB; }
        .card-hover { transition: transform 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); }
    </style>
    
    <?php if ($isChristmasWeek): ?>
        <script src="https://app.embed.im/snow.js" defer></script>
    <?php endif; ?>
</head>
<body class="bg-gray-900 text-gray-200 min-h-screen flex flex-col">

    <nav class="bg-gray-800 border-b border-gray-700 shadow-lg" x-data="{ isOpen: false }">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <img src="/assets/img/logo.png" alt="Logo" class="h-12 w-12">
                    <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-white">Sistema de Reserva de Salones</h1>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <?php if ($loggedIn): ?>
                        <span class="text-gray-300 mr-2">Hola, <?php echo htmlspecialchars($userName); ?></span>
                        <a href="/reservations/create" class="text-white bg-blue-600 hover:bg-blue-700 font-medium px-4 py-2 rounded-lg transition-all">Reservar</a>
                        <a href="/logout" class="text-white bg-red-600 hover:bg-red-700 font-medium px-4 py-2 rounded-lg transition-all">Cerrar sesión</a>
                    <?php else: ?>
                        <a href="/login" class="text-white bg-gray-700 hover:bg-gray-600 font-medium px-4 py-2 rounded-lg">Iniciar sesión</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">
        <?php echo $content; ?>
    </main>

    <footer class="bg-gray-800 border-t border-gray-700 py-6 mt-10">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> - Sistema de Reserva de Salones V2</p>
        </div>
    </footer>
</body>
</html>
