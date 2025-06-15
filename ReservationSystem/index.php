<?php
session_start();
$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true;
$esAdmin = isset($_SESSION['EsAdmin']) && $_SESSION['EsAdmin'] == true;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Salones</title>

    <link rel="icon" href="https://i.imgur.com/fSjgaVI.jpeg" type="image/svg+xml">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- jQuery and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>

    <!-- Alpine.js for UI interactions -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <!-- Heroicons -->
    <script src="https://unpkg.com/@heroicons/core@2.0.0/24/outline/esm/index.js"></script>

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
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
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

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <?php

    ob_start();

    // Registrar función para capturar errores fatales
    register_shutdown_function(function () {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            ob_clean();
            header('Location: maintenance.html');
            exit;
        }
    });

    // Detectar si estamos en localhost
    $isLocalhost = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']);

    // Configuración de errores según el entorno
    if ($isLocalhost) {
        ini_set('display_errors', 1); // Mostrar errores
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', 0); // Ocultar errores en producción
        error_reporting(E_ALL);
    }

    include('include/conexion.php');

    // Set timezone to Argentina
    date_default_timezone_set('America/Argentina/Buenos_Aires');

    $fecha_actual = date('Y/m/d');

    function hayRegistrosDisponibles($conexion)
    {
        $consulta = "SELECT COUNT(*) as count FROM tabla";
        $resultado = mysqli_query($conexion, $consulta);
        $fila = mysqli_fetch_assoc($resultado);
        return $fila['count'] > 0;
    }

    $hayRegistros = hayRegistrosDisponibles($conexion);

    // Pagination configuration
    $items_per_page = 10;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $items_per_page;

    $consulta_borrar = "DELETE FROM tabla WHERE fecha < DATE_SUB('" . $fecha_actual . "', INTERVAL 7 DAY)";
    mysqli_query($conexion, $consulta_borrar) or die('Error en consulta de fechas');

    $consulta_ordenada = " SELECT *, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha_formateada 
    FROM tabla 
    ORDER BY 
    ABS(DATEDIFF(fecha, '$fecha_actual')) ASC,
    TIME(horario) ASC
    LIMIT $offset, $items_per_page
    ";

    $resultado = mysqli_query($conexion, $consulta_ordenada) or die('Error en consulta');
    $num_filas = mysqli_num_rows($resultado);

    mysqli_close($conexion);
    ?>

    <?php
    $today = new DateTime();
    $month = (int) $today->format('m');
    $day = (int) $today->format('d');

    $isChristmasWeek = $month === 12 && $day >= 20 && $day <= 26;

    if ($isChristmasWeek) {
        echo '<script src="https://app.embed.im/snow.js" defer></script>';
    }
    ?>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Montserrat', sans-serif;
        }

        /* FullCalendar dark theme customization */
        .fc {
            --fc-page-bg-color: #121826;
            --fc-border-color: #374151;
            --fc-neutral-bg-color: #1F2937;
            --fc-list-event-hover-bg-color: #374151;
            --fc-button-text-color: #F9FAFB;
            --fc-button-bg-color: #2563EB;
            --fc-button-border-color: #2563EB;
            --fc-button-hover-bg-color: #1D4ED8;
            --fc-button-hover-border-color: #1D4ED8;
            --fc-button-active-bg-color: #1E40AF;
            --fc-button-active-border-color: #1E40AF;
            --fc-event-bg-color: #2563EB;
            --fc-event-border-color: #2563EB;
            --fc-event-text-color: #F9FAFB;
            --fc-today-bg-color: rgba(37, 99, 235, 0.1);
            --fc-neutral-text-color: #E5E7EB;
        }

        .fc-theme-standard th {
            background-color: #1F2937;
            color: #F9FAFB;
        }

        .fc-scrollgrid-sync-inner a,
        .fc-col-header-cell-cushion {
            color: #F9FAFB !important;
            text-decoration: none !important;
        }

        .fc-daygrid-day-number {
            color: #F9FAFB !important;
            text-decoration: none !important;
        }

        .fc-day-today {
            background-color: rgba(37, 99, 235, 0.1) !important;
        }

        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }

        /* Card hover effects */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date();
            const isChristmasWeek = today.getMonth() === 11 && today.getDate() >= 20 && today.getDate() <= 26;

            if (isChristmasWeek) {
                document.body.classList.add('navidad');
            }

            // Initialize FullCalendar
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                },
                slotMinTime: '07:00:00',
                slotMaxTime: '23:00:00',
                allDaySlot: false,
                height: 'auto',
                events: 'get_events.php',
                eventClick: function (info) {
                    showEventDetails(info.event);
                },
                eventDidMount: function (info) {
                    // Assign a unique color based on event ID or type
                    const eventColors = {
                        'AUDIOVISUALES': '#3B82F6', // blue
                        'COMEDOR': '#10B981',      // green
                        'SALÓN DE ACTOS': '#F59E0B' // amber
                    };

                    let color;
                    if (info.event.extendedProps.info && eventColors[info.event.extendedProps.info]) {
                        color = eventColors[info.event.extendedProps.info];
                    } else {
                        // Fallback color assignment
                        const colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'];
                        const eventId = parseInt(info.event.id, 10);
                        color = colors[eventId % colors.length];
                    }

                    info.el.style.backgroundColor = color;
                    info.el.style.borderColor = color;
                },
                eventOverlap: false,
                slotEventOverlap: false,
                eventConstraint: {
                    start: '07:00',
                    end: '23:00'
                },
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                }
            });
            calendar.render();

            function showEventDetails(event) {
                // Get the modal element
                const modalEl = document.getElementById('eventModal');

                // Update modal content
                document.getElementById('eventDetails').innerHTML = `
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-blue-500">
                                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                            </svg>

                            <p class="text-gray-200"><span class="font-medium">Nombre y Apellido:</span> ${event.extendedProps.nombreapellido}</p>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            ${event.extendedProps.curso.startsWith('Charla') || event.extendedProps.curso === 'Reunión' || event.extendedProps.curso === 'Acto'
                        ? `
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-green-500">
                                <path fill-rule="evenodd" d="M7.5 5.25a3 3 0 0 1 3-3h3a3 3 0 0 1 3 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0 1 12 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 0 1 7.5 5.455V5.25Zm7.5 0v.09a49.488 49.488 0 0 0-6 0v-.09a1.5 1.5 0 0 1 1.5-1.5h3a1.5 1.5 0 0 1 1.5 1.5Zm-3 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                <path d="M3 18.4v-2.796a4.3 4.3 0 0 0 .713.31A26.226 26.226 0 0 0 12 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 0 1-6.477-.427C4.047 21.128 3 19.852 3 18.4Z" />
                            </svg>`
                        : `
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-green-500">
                                <path d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z" />
                                <path d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z" />
                                <path d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z" />
                            </svg>`}
    
                            <p class="text-gray-200">
                                <span class="font-medium">
                                    ${event.extendedProps.curso.startsWith('Charla') || event.extendedProps.curso === 'Reunión' || event.extendedProps.curso === 'Acto' ? 'Evento' : 'Curso'}:
                                </span>
                                ${event.extendedProps.curso}
                            </p>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            ${event.extendedProps.curso.startsWith('Charla') || event.extendedProps.curso === 'Reunión' || event.extendedProps.curso === 'Acto'
                        ? `
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-purple-500">
                            <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd" />
                            <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                        </svg>
                        `
                        : `
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-purple-500">
                            <path d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z" />
                        </svg>
                        `
                    }

                        <p class="text-gray-200">
                            <span class="font-medium">
                                ${event.extendedProps.curso.startsWith('Charla') || event.extendedProps.curso === 'Reunión' || event.extendedProps.curso === 'Acto' ? 'Motivo' : 'Materia'}:
                            </span> 
                            ${event.extendedProps.materia}
                        </p>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-yellow-500">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-gray-200"><span class="font-medium">Horario:</span> ${event.start.toLocaleTimeString('es-AR', { hour: '2-digit', minute: '2-digit', hour12: false })} - ${event.end.toLocaleTimeString('es-AR', { hour: '2-digit', minute: '2-digit', hour12: false })}</p>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-red-500">
                                <path d="M11.584 2.376a.75.75 0 0 1 .832 0l9 6a.75.75 0 1 1-.832 1.248L12 3.901 3.416 9.624a.75.75 0 0 1-.832-1.248l9-6Z" />
                                <path fill-rule="evenodd" d="M20.25 10.332v9.918H21a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1 0-1.5h.75v-9.918a.75.75 0 0 1 .634-.74A49.109 49.109 0 0 1 12 9c2.59 0 5.134.202 7.616.592a.75.75 0 0 1 .634.74Zm-7.5 2.418a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Zm3-.75a.75.75 0 0 1 .75.75v6.75a.75.75 0 0 1-1.5 0v-6.75a.75.75 0 0 1 .75-.75ZM9 12.75a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Z" clip-rule="evenodd" />
                                <path d="M12 7.875a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" />
                            </svg>
                            <p class="text-gray-200"><span class="font-medium">Salón:</span> ${event.extendedProps.info}</p>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-indigo-500">
                                <path d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                                <path fill-rule="evenodd" d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087Zm6.163 3.75A.75.75 0 0 1 10 12h4a.75.75 0 0 1 0 1.5h-4a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-gray-200"><span class="font-medium">Materiales:</span> ${event.extendedProps.materiales}</p>
                        </div>
                    </div>
                `;

                // Handle edit button if admin
                if (document.getElementById('editEvent')) {
                    document.getElementById('editEvent').onclick = () => {
                        window.location.href = `reserva/modifica_reserva.php?id=${event.extendedProps.id}`;
                    };
                }

                // Handle delete button if admin
                if (document.getElementById('deleteEvent')) {
                    document.getElementById('deleteEvent').onclick = () => {
                        if (confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
                            window.location.href = `reserva/baja_sql.php?id=${event.extendedProps.id}`;
                        }
                    };
                }

                // Set variables for Alpine.js to show modal
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'eventModal' }));
            }

            // Initialize datepicker defaults for print modal
            $.ajax({
                url: 'get_oldest_date.php',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.oldest_date) {
                        var oldestDate = response.oldest_date;
                        var today = moment().format('YYYY-MM-DD');

                        $('#startDate').val(oldestDate);
                        $('#startDate').attr('min', oldestDate);
                        $('#startDate').attr('max', today);

                        $('#endDate').val(today);
                        $('#endDate').attr('min', oldestDate);
                        $('#endDate').attr('max', today);
                    } else {
                        console.error('Error al obtener la fecha más antigua:', response.error);
                    }
                },
                error: function (error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });

            // Handle print form submission
            $('#printForm').on('submit', function (e) {
                e.preventDefault();

                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();

                var data = {
                    startDate: startDate,
                    endDate: endDate
                };

                $.ajax({
                    url: 'generate_pdf.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    dataType: 'json',
                    success: function (response) {
                        if (response.pdf) {
                            var pdfUrl = response.pdf;
                            var win = window.open(pdfUrl, '_blank');
                            if (win) {
                                // Try to delete PDF after some time
                                setTimeout(function () {
                                    deletePDF(pdfUrl);
                                }, 10000);

                                // Also try to delete when window closes
                                $(window).on('focus', function () {
                                    if (win.closed) {
                                        deletePDF(pdfUrl);
                                    }
                                });
                            } else {
                                console.error('Error al abrir una nueva pestaña. Verifica que tu navegador no esté bloqueando las ventanas emergentes.');
                            }
                        } else {
                            console.error('Error al generar el PDF:', response.error);
                        }
                    },
                    error: function (error) {
                        console.error('Error en la solicitud AJAX:', error);
                    }
                });
            });

            function deletePDF(pdfUrl) {
                $.ajax({
                    url: 'delete_pdf.php',
                    type: 'POST',
                    data: JSON.stringify({
                        pdf: pdfUrl
                    }),
                    contentType: 'application/json',
                    success: function (deleteResponse) {
                        console.log('Intento de eliminación del PDF completado');
                    },
                    error: function (deleteError) {
                        console.error('Error al eliminar el PDF:', deleteError);
                    }
                });
            }
        });
    </script>
</head>

<body class="bg-gray-900 text-gray-200 min-h-screen flex flex-col" x-data="{ 
    showEventModal: false,
    showPrintModal: false,
    showPrintQrModal: false,
    showErrorModal: <?php echo isset($_GET['error']) ? 'true' : 'false'; ?>,
    errorMessage: '<?php echo isset($_GET['error']) ? htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') : ''; ?>'
}"
    @open-modal.window="$event.detail === 'eventModal' ? showEventModal = true : ($event.detail === 'printModal' ? showPrintModal = true : ($event.detail === 'printQrModal' ? showPrintQrModal = true : null))">

    <!-- Error Modal -->
    <div x-show="showErrorModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" @click="showErrorModal = false"></div>

        <div class="relative bg-gray-800 rounded-lg max-w-md w-full mx-4 overflow-hidden shadow-xl transform transition-all"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4">
            <div class="px-4 pt-5 pb-4 sm:p-6">
                <!-- Error Icon and Header -->
                <div class="flex items-center mb-4">
                    <div
                        class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-medium leading-6 text-white">Error</h3>
                    <button type="button" class="ml-auto text-gray-400 hover:text-white"
                        @click="showErrorModal = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Error Message -->
                <div class="mt-3 text-center sm:mt-5">
                    <div class="mt-2">
                        <p class="text-gray-300" x-text="errorMessage"></p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-5 sm:mt-6 flex justify-center">
                    <button type="button"
                        class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm transition-colors duration-200"
                        @click="showErrorModal = false">
                        Entendido
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Bar -->
    <nav class="bg-gray-800 border-b border-gray-700 shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <img src="img/logo.png" alt="Logo" class="h-12 w-12">
                    <h1 class="text-xl md:text-2xl font-bold text-white">Sistema de Reserva de Salones</h1>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <?php if ($loggedIn): ?>
                        <a href="reserva/cargar_reserva.php?tabla="
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium px-4 py-2 rounded-lg transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Reservar
                        </a>

                        <a href="logout.php"
                            class="text-white bg-red-600 hover:bg-red-700 font-medium px-4 py-2 rounded-lg transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            Cerrar sesión
                        </a>
                    <?php else: ?>
                        <a href="iniciar_sesion.php"
                            class="text-white bg-gray-700 hover:bg-gray-600 font-medium px-4 py-2 rounded-lg transition-all flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                            Iniciar sesión
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-300 hover:text-white" x-data="{ isOpen: false }"
                        @click="isOpen = !isOpen" x-init="$watch('isOpen', value => {
                        if (value) {
                            document.getElementById('mobile-menu').classList.remove('hidden');
                        } else {
                            document.getElementById('mobile-menu').classList.add('hidden');
                        }
                    })">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-700">
                <?php if ($loggedIn): ?>
                    <a href="reserva/cargar_reserva.php?tabla="
                        class="block text-white bg-blue-600 hover:bg-blue-700 font-medium px-3 py-2 rounded-md text-center">
                        Reservar
                    </a>

                    <a href="logout.php"
                        class="block text-white bg-red-600 hover:bg-red-700 font-medium px-3 py-2 rounded-md text-center mt-2">
                        Cerrar sesión
                    </a>
                <?php else: ?>
                    <a href="iniciar_sesion.php"
                        class="block text-white bg-gray-700 hover:bg-gray-600 font-medium px-3 py-2 rounded-md text-center">
                        Iniciar sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">
        <!-- Hero section -->
        <section class="mb-12">
            <div class="bg-gradient-to-r from-blue-800 to-indigo-900 rounded-2xl shadow-2xl p-8 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Sistema de Reserva de Salones</h2>
                <p class="text-lg text-blue-100 max-w-3xl mx-auto">
                    Reserve salones para clases, eventos y actividades. Consulte disponibilidad en tiempo real.
                </p>

                <?php if ($loggedIn): ?>
                    <a href="reserva/cargar_reserva.php?tabla="
                        class="mt-6 inline-block bg-white text-blue-800 hover:bg-blue-50 font-medium px-6 py-3 rounded-lg transition-all shadow-md">
                        Crear nueva reserva
                    </a>
                <?php else: ?>
                    <a href="iniciar_sesion.php"
                        class="mt-6 inline-block bg-white text-blue-800 hover:bg-blue-50 font-medium px-6 py-3 rounded-lg transition-all shadow-md">
                        Iniciar sesión para reservar
                    </a>
                <?php endif; ?>
            </div>
        </section>

        <!-- Salas disponibles section -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-white mb-6 border-b border-gray-700 pb-2">Salones Disponibles</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1: Audiovisuales -->
                <div
                    class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-xl overflow-hidden card-hover">
                    <img src="img/audio_visuales.webp" class="h-48 w-full object-cover" alt="Sala de Audiovisuales">
                    <div class="p-6">
                        <div class="flex items-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="h-6 w-6 text-blue-500 mr-2">
                                <path fill-rule="evenodd"
                                    d="M1.5 5.625c0-1.036.84-1.875 1.875-1.875h17.25c1.035 0 1.875.84 1.875 1.875v12.75c0 1.035-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 18.375V5.625Zm1.5 0v1.5c0 .207.168.375.375.375h1.5a.375.375 0 0 0 .375-.375v-1.5a.375.375 0 0 0-.375-.375h-1.5A.375.375 0 0 0 3 5.625Zm16.125-.375a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h1.5A.375.375 0 0 0 21 7.125v-1.5a.375.375 0 0 0-.375-.375h-1.5ZM21 9.375A.375.375 0 0 0 20.625 9h-1.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h1.5a.375.375 0 0 0 .375-.375v-1.5Zm0 3.75a.375.375 0 0 0-.375-.375h-1.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h1.5a.375.375 0 0 0 .375-.375v-1.5Zm0 3.75a.375.375 0 0 0-.375-.375h-1.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h1.5a.375.375 0 0 0 .375-.375v-1.5ZM4.875 18.75a.375.375 0 0 0 .375-.375v-1.5a.375.375 0 0 0-.375-.375h-1.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h1.5ZM3.375 15h1.5a.375.375 0 0 0 .375-.375v-1.5a.375.375 0 0 0-.375-.375h-1.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375Zm0-3.75h1.5a.375.375 0 0 0 .375-.375v-1.5A.375.375 0 0 0 4.875 9h-1.5A.375.375 0 0 0 3 9.375v1.5c0 .207.168.375.375.375Zm4.125 0a.75.75 0 0 0 0 1.5h9a.75.75 0 0 0 0-1.5h-9Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <h3 class="text-xl font-bold text-white">AUDIOVISUALES</h3>
                        </div>
                        <p class="text-gray-300 mb-4">Esta sala tiene una capacidad máxima de 30 personas. Se recomienda
                            reservar con amplia anticipación.</p>
                        <div class="flex justify-between text-sm">
                            <span class="flex items-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                </svg>
                                Capacidad: 30
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Comedor -->
                <div
                    class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-xl overflow-hidden card-hover">
                    <img src="img/comedor.webp" class="h-48 w-full object-cover" alt="Comedor">
                    <div class="p-6">
                        <div class="flex items-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="h-6 w-6 text-green-500 mr-2">
                                <path d="M19.5 6h-15v9h15V6Z" />
                                <path fill-rule="evenodd"
                                    d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v11.25C1.5 17.16 2.34 18 3.375 18H9.75v1.5H6A.75.75 0 0 0 6 21h12a.75.75 0 0 0 0-1.5h-3.75V18h6.375c1.035 0 1.875-.84 1.875-1.875V4.875C22.5 3.839 21.66 3 20.625 3H3.375Zm0 13.5h17.25a.375.375 0 0 0 .375-.375V4.875a.375.375 0 0 0-.375-.375H3.375A.375.375 0 0 0 3 4.875v11.25c0 .207.168.375.375.375Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <h3 class="text-xl font-bold text-white">COMEDOR</h3>
                        </div>
                        <p class="text-gray-300 mb-4">Esta sala tiene capacidad aproximada de 50 personas o más. Pueden
                            asistir grupos de Aula individualmente o en conjunto.</p>
                        <div class="flex justify-between text-sm">
                            <span class="flex items-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                </svg>
                                Capacidad: 50+
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Salón de Actos -->
                <div
                    class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-xl overflow-hidden card-hover">
                    <img src="img/actos.webp" class="h-48 w-full object-cover" alt="Salón de Actos">
                    <div class="p-6">
                        <div class="flex items-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="h-6 w-6 text-yellow-500 mr-2">
                                <path
                                    d="M16.881 4.345A23.112 23.112 0 0 1 8.25 6H7.5a5.25 5.25 0 0 0-.88 10.427 21.593 21.593 0 0 0 1.378 3.94c.464 1.004 1.674 1.32 2.582.796l.657-.379c.88-.508 1.165-1.593.772-2.468a17.116 17.116 0 0 1-.628-1.607c1.918.258 3.76.75 5.5 1.446A21.727 21.727 0 0 0 18 11.25c0-2.414-.393-4.735-1.119-6.905ZM18.26 3.74a23.22 23.22 0 0 1 1.24 7.51 23.22 23.22 0 0 1-1.41 7.992.75.75 0 1 0 1.409.516 24.555 24.555 0 0 0 1.415-6.43 2.992 2.992 0 0 0 .836-2.078c0-.807-.319-1.54-.836-2.078a24.65 24.65 0 0 0-1.415-6.43.75.75 0 1 0-1.409.516c.059.16.116.321.17.483Z" />
                            </svg>
                            <h3 class="text-xl font-bold text-white">SALÓN DE ACTOS</h3>
                        </div>
                        <p class="text-gray-300 mb-4">Esta sala tiene capacidad aproximada de 100 personas o más. Pueden
                            asistir grupos de Aula individualmente o en conjunto.</p>
                        <div class="flex justify-between text-sm">
                            <span class="flex items-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                </svg>
                                Capacidad: 100+
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Calendar section -->
        <section class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-white border-b border-gray-700 pb-2">Calendario de Reservas</h2>

                <?php if ($loggedIn): ?>
                    <a href="reserva/cargar_reserva.php?tabla="
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition-all flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                clip-rule="evenodd" />
                        </svg>
                        Nueva Reserva
                    </a>
                <?php endif; ?>
            </div>

            <?php if ($num_filas > 0): ?>
                <div class="bg-gray-800 rounded-xl shadow-xl p-4 border border-gray-700">
                    <div id="calendar" class="calendar-container"></div>
                </div>
            <?php else: ?>
                <div class="bg-gray-800 rounded-xl shadow-xl p-6 border border-gray-700 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-600 mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-xl font-bold text-white mb-2">No hay turnos disponibles</h3>
                    <p class="text-gray-400 mb-4">En este momento no hay turnos registrados. ¡Sé el primero en reservar un
                        salón!</p>

                    <?php if ($loggedIn): ?>
                        <a href="reserva/cargar_reserva.php?tabla="
                            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                    clip-rule="evenodd" />
                            </svg>
                            Hacer una reserva
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>

        <!-- Admin buttons -->
        <?php if ($esAdmin): ?>
            <section class="mb-8">
                <h2 class="text-2xl font-bold text-white mb-6 border-b border-gray-700 pb-2">Administración</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button type="button"
                        class="bg-gray-700 hover:bg-gray-600 text-white font-medium py-3 px-4 rounded-lg shadow transition-all flex items-center justify-center"
                        @click="showPrintModal = true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                clip-rule="evenodd" />
                        </svg>
                        Imprimir Registros
                    </button>

                    <button type="button"
                        class="bg-indigo-700 hover:bg-indigo-600 text-white font-medium py-3 px-4 rounded-lg shadow transition-all flex items-center justify-center"
                        @click="showPrintQrModal = true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 3a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3zm1 2v1h1V5h-1z"
                                clip-rule="evenodd" />
                            <path
                                d="M11 4a1 1 0 10-2 0v1a1 1 0 002 0V4zM10 7a1 1 0 011 1v1h2a1 1 0 110 2h-3a1 1 0 01-1-1V8a1 1 0 011-1zM16 9a1 1 0 100 2 1 1 0 000-2zM9 13a1 1 0 011-1h1a1 1 0 110 2v2a1 1 0 11-2 0v-3zM7 11a1 1 0 100-2H4a1 1 0 100 2h3zM17 13a1 1 0 01-1 1h-2a1 1 0 110-2h2a1 1 0 011 1zM16 17a1 1 0 100-2h-3a1 1 0 100 2h3z" />
                        </svg>
                        Generar QR
                    </button>

                    <a href="admin/gestion.php"
                        class="bg-amber-600 hover:bg-amber-500 text-white font-medium py-3 px-4 rounded-lg shadow transition-all flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                        </svg>
                        Gestionar Usuarios
                    </a>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <!-- Footer rediseñado -->
    <footer class="bg-gray-800 border-t border-gray-700 py-6 mt-10">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0 flex items-center">
                    <img src="https://i.imgur.com/fSjgaVI.jpeg" alt="Logo"
                        class="h-10 w-10 mr-3 rounded-full border border-gray-600">
                    <div>
                        <span class="text-gray-200 font-medium">Sistema de Reserva de Salones</span>
                        <p class="text-gray-400 text-xs">Gestión eficiente de espacios educativos</p>
                    </div>
                </div>

                <div class="text-center md:text-right">
                    <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> - Sistema de Reserva de Salones V2
                    </p>
                    <p class="text-gray-500 text-xs mt-1">Desarrollado por Bernardo G. Erramuspe, estudiante de 7mo año
                        en 2024</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Event Details Modal -->
    <div x-show="showEventModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" @click="showEventModal = false"></div>

        <div class="relative bg-gray-800 rounded-lg max-w-lg w-full mx-4 overflow-hidden shadow-xl transform transition-all"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4">
            <div class="px-4 pt-5 pb-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium leading-6 text-white">Detalles de la Reserva</h3>
                    <button type="button" class="text-gray-400 hover:text-white" @click="showEventModal = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div id="eventDetails" class="mt-2"></div>

                <div class="mt-5 sm:mt-6 flex justify-end space-x-2">
                    <?php if ($esAdmin): ?>
                        <button type="button" id="editEvent"
                            class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:text-sm">
                            Editar
                        </button>
                        <button type="button" id="deleteEvent"
                            class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                            Eliminar
                        </button>
                    <?php endif; ?>
                    <button type="button"
                        class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm"
                        @click="showEventModal = false">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Print QR Modal -->
    <div x-show="showPrintQrModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" @click="showPrintQrModal = false"></div>

        <div class="relative bg-gray-800 rounded-lg max-w-md w-full mx-4 overflow-hidden shadow-xl transform transition-all"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4">
            <div class="px-4 pt-5 pb-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium leading-6 text-white">Código QR del Sistema</h3>
                    <button type="button" class="text-gray-400 hover:text-white" @click="showPrintQrModal = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 flex flex-col items-center">
                    <div class="bg-white p-4 rounded-lg mb-6">
                        <img src="generatePrintQR.php" alt="QR Code" class="w-64 h-64">
                    </div>

                    <p class="text-gray-300 text-sm mb-5 text-center">Escanee este código QR para acceder rápidamente al
                        sistema de reservas.</p>

                    <button onclick="window.open('generatePrintQR.php?print=true', '_blank', 'width=800,height=800')"
                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                clip-rule="evenodd" />
                        </svg>
                        Imprimir Código QR
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Records Modal -->
    <div x-show="showPrintModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" @click="showPrintModal = false"></div>

        <div class="relative bg-gray-800 rounded-lg max-w-md w-full mx-4 overflow-hidden shadow-xl transform transition-all"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4">
            <div class="px-4 pt-5 pb-4 sm:p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium leading-6 text-white">Imprimir Registros</h3>
                    <button type="button" class="text-gray-400 hover:text-white" @click="showPrintModal = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <?php if ($hayRegistros): ?>
                    <form id="printForm" class="mt-4">
                        <div class="mb-4">
                            <label for="startDate" class="block text-sm font-medium text-gray-300">Fecha de Inicio</label>
                            <input type="date"
                                class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                id="startDate" name="startDate" required>
                        </div>

                        <div class="mb-4">
                            <label for="endDate" class="block text-sm font-medium text-gray-300">Fecha de Fin</label>
                            <input type="date"
                                class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                id="endDate" name="endDate" required>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Generar PDF
                        </button>
                    </form>
                <?php else: ?>
                    <p class="text-gray-400 text-center">No hay registros disponibles para imprimir.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>