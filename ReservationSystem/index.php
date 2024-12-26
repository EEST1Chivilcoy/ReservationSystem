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
    <title>Inicio</title>
    <link rel="stylesheet" href="css\bootstrap.min.css">
    <link rel="stylesheet" href="css\estilo.css">
    <link rel="icon" href="img\logo.png" type="image/svg+xml">

    <!-- jQuery y Popper.js (requeridos para Bootstrap JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Moment.js (para manejar fechas) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- FullCalendar (Calendario) -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <?php
    include('include/conexion.php');

    // Configurar la zona horaria a Argentina
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

    // Configuración de paginación
    $items_per_page = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $items_per_page;

    $consulta_borrar = "DELETE FROM tabla WHERE fecha < DATE_SUB('" . $fecha_actual . "', INTERVAL 7 DAY)"; //Ahora esta consulta borra despues de 7 Dias (Una semana)
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
    $month = (int)$today->format('m');
    $day = (int)$today->format('d');

    $isChristmasWeek = $month === 12 && $day >= 20 && $day <= 26;

    if ($isChristmasWeek) {
        echo '<script src="https://app.embed.im/snow.js" defer></script>';
    }
    ?>

    <style>
        .card {
            background-color: rgb(161, 192, 220);
        }

        .logo {
            text-align: center;
        }

        p {
            color: white;
        }

        .alert-custom-dark {
            background-color: #343a40;
            color: #ffffff;
            border-color: #454d55;
        }

        .alert-custom-dark hr {
            border-top-color: #ffffff;
        }

        .alert-custom-dark .alert-link {
            color: #e9ecef;
        }

        /* Estilos personalizados para el modal en modo oscuro */
        #printModal .modal-content {
            background-color: #343a40;
            color: #f8f9fa;
        }

        #printModal .close {
            color: #f8f9fa;
        }

        #printModal .form-control {
            background-color: #495057;
            color: #f8f9fa;
            border-color: #6c757d;
        }

        #printModal .form-control:focus {
            background-color: #495057;
            color: #f8f9fa;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        #printModal .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        #printModal .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        /* Estilo para los campos de fecha en webkit browsers */
        #printModal input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        /* Estilos para los campos de fecha en modo oscuro */
        #printModal .form-control[type="date"] {
            background-color: #495057;
            color: #fff;
            border-color: #6c757d;
        }

        #printModal .form-control[type="date"]:focus {
            background-color: #495057;
            color: #fff;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Estilo para el texto del placeholder y los separadores */
        #printModal .form-control[type="date"]::-webkit-datetime-edit {
            color: #fff;
        }

        #printModal .form-control[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        /* Estilos para Firefox */
        #printModal .form-control[type="date"] {
            color-scheme: dark;
        }

        /* Iconos en la tabla */
        .btn-link {
            display: inline-flex;
            align-items: center;
            margin: 0;
            padding: 0;
            color: white;
        }

        /* Estilos base del calendario */
        .fc {
            background-color: #1a2635;
            color: #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(161, 192, 220, 0.2);
        }

        /* Encabezados y bordes */
        .fc-theme-standard td,
        .fc-theme-standard th,
        .fc-theme-standard .fc-scrollgrid {
            border-color: rgba(161, 192, 220, 0.2);
        }

        .fc-col-header {
            background-color: #243447;
        }

        /* Botones */
        .fc .fc-button {
            background-color: #243447;
            border-color: rgba(161, 192, 220, 0.2);
            color: #e0e0e0;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .fc .fc-button:hover {
            background-color: #2c3e50;
            border-color: rgba(161, 192, 220, 0.4);
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background-color: #34495e;
            border-color: rgba(161, 192, 220, 0.4);
        }

        /* Día actual */
        .fc-day-today {
            background-color: rgba(161, 192, 220, 0.15) !important;
        }

        /* Números y texto */
        .fc-daygrid-day-number,
        .fc-col-header-cell-cushion {
            color: #e0e0e0;
            text-decoration: none !important;
        }

        /* Eventos */
        .fc-event {
            background-color: rgb(161, 192, 220);
            border-color: rgb(161, 192, 220);
            color: #1a2635;
            transition: transform 0.2s ease;
        }

        .fc-event:hover {
            transform: scale(1.02);
        }

        /* Día no del mes actual */
        .fc-day-other {
            background-color: #1c2837;
        }

        /* Hover sobre días */
        .fc-daygrid-day:hover {
            background-color: rgba(161, 192, 220, 0.05);
        }

        /* Popover de eventos */
        .fc-popover {
            background-color: #243447;
            border-color: rgba(161, 192, 220, 0.2);
        }

        .fc-popover-header {
            background-color: #2c3e50;
            color: #e0e0e0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const isChristmasWeek = today.getMonth() === 11 && today.getDate() >= 20 && today.getDate() <= 26;

            if (isChristmasWeek) {
                document.body.classList.add('navidad');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
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
                eventClick: function(info) {
                    showEventDetails(info.event);
                }
            });
            calendar.render();

            function showEventDetails(event) {
                const details = `
                    <p><strong>Nombre:</strong> ${event.extendedProps.nombreapellido}</p>
                    <p><strong>Curso:</strong> ${event.extendedProps.curso}</p>
                    <p><strong>Materia:</strong> ${event.extendedProps.materia}</p>
                    <p><strong>Salón:</strong> ${event.extendedProps.info}</p>
                    <p><strong>Materiales:</strong> ${event.extendedProps.materiales}</p>
                `;

                document.getElementById('eventDetails').innerHTML = details;

                const modal = new bootstrap.Modal(document.getElementById('eventModal'));
                modal.show();

                if (document.getElementById('editEvent')) {
                    document.getElementById('editEvent').onclick = () => {
                        window.location.href = `reserva/modifica_reserva.php?id=${event.extendedProps.id}`;
                    };
                }

                if (document.getElementById('deleteEvent')) {
                    document.getElementById('deleteEvent').onclick = () => {
                        if (confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
                            window.location.href = `reserva/baja_sql.php?id=${event.extendedProps.id}`;
                        }
                    };
                }
            }
        });
    </script>
</head>

<body>
    <nav class="navbar bg-body-tertiary custom-navbar">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="#">
                    <img src="img\logo.png" alt="Logo" width="65" height="65" class="d-inline-block align-text-top">
                </a>
                <h1 class="navbar-text text-center font-italic mb-0 custom-title">RESERVA DE SALONES</h1>
            </div>
        </div>
    </nav>

    <br>
    <h3 class="text-center font-italic" style="color: white">INFO</h3>
    <br>


    <div class="card-group">

        <div class="card mb-3 mx-2 rounded" style="background:#696969">

            <img src="img/audio_visuales.jpg" class="card-img-top rounded" alt="...">

            <div class="card-body">
                <h3 class="card-title" style="color:white; text-align:center;">AUDIOVISUALES</h3>
                <p class="card-text">Esta sala tiene una capacidad máxima de 30 personas.
                    Se recomienda reservar con amplia anticipación.</p>
            </div>

        </div>

        <div class="card mb-3 mx-2 rounded" style="background:#545454">

            <img src="img/comedor.jpg" class="card-img-top rounded" alt="...">

            <div class="card-body">
                <h3 class="card-title" style="color:white; text-align:center;">COMEDOR</h3>
                <p class="card-text">Esta sala tiene capacidad aproximada de 50 personas o más.
                    Pueden asistir grupos de Aula individualmente o en conjunto.</p>
            </div>

        </div>

        <div class="card mb-3 mx-2 rounded" style="background:#696969">

            <img src="img/actos.jpg" class="card-img-top rounded" alt="...">

            <div class="card-body">
                <h3 class="card-title" style="color:white; text-align:center;">SALÓN DE ACTOS</h3>
                <p class="card-text">Esta sala tiene capacidad aproximada de 100 personas o más.
                    Pueden asistir grupos de Aula individualmente o en conjunto.</p>
            </div>

        </div>

    </div>

    <?php if ($loggedIn) : ?>

        <br>

        <div class="d-flex justify-content-center">
            <a href="reserva/cargar_reserva.php?tabla=" class="btn btn-primary btn-lg w-50 position-relative" style="color:white; text-decoration: none;">
                <i class="bi bi-calendar-check-fill"></i> Reservar
            </a>
        </div>

        <br>

        <div class="d-flex justify-content-center">
            <a class="btn btn-danger btn-lg w-50 position-relative" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a>
        </div>

    <?php else : ?>

        <br>

        <div class="d-flex justify-content-center">
            <a class="btn btn-dark btn-lg w-50 position-relative" href="iniciar_sesion.php">
                <i class="bi bi-person-fill"></i> Iniciar sesión / Registrarse
            </a>
        </div>

    <?php endif; ?>

    <br>

    <div class="titulo">
        <h1 class="text-center font-italic" style="color:white;">TURNOS</h1>
    </div>

    </div>

    <br>

    <!-- Fila 2 (Tabla)-->
    <div class="container">

        <div class="row">

            <div class="col-1"></div>

            <div class="col-10">

                <?php if ($num_filas > 0) : ?>

                    <div class="container">
                        <div id='calendar'></div>
                    </div>

                <?php else : ?>
                    <div class="alert alert-custom-dark text-center" role="alert">
                        <h4 class="alert-heading">No hay turnos disponibles</h4>
                        <p>En este momento no hay turnos registrados. ¡Sé el primero en reservar un salón!</p>
                        <?php if ($loggedIn) : ?>
                            <hr>
                            <p class="mb-0">
                                <a href="reserva/cargar_reserva.php?tabla=" class="btn btn-light">
                                    <i class="bi bi-calendar-check-fill"></i> Hacer una reserva
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-1">
            </div>
        </div>
    </div>

    <?php if ($esAdmin) : ?>

        <br>

        <div class="d-flex justify-content-center mt-5">
            <button type="button" class="btn btn-secondary btn-lg w-50 position-relative" data-toggle="modal" data-target="#printModal">
                <i class="bi bi-printer-fill"></i> Imprimir Registros
            </button>
        </div>

        <br>

        <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-info btn-lg w-50 position-relative" data-toggle="modal" data-target="#printQrModal">
                <i class="bi bi-qr-code"></i> Generar QR
            </button>
        </div>

        <br>

        <div class="d-flex justify-content-center">
            <a href="Admin/gestion.php" class="btn btn-warning btn-lg w-50 position-relative">
                <i class="bi bi-pencil-square"></i> Modificar Usuarios
            </a>
        </div>

        <br>

    <?php endif; ?>

    <br>

    <?php
    include('footer.php');
    ?>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de la Reserva</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="eventDetails"></div>
                <div class="modal-footer">
                    <?php if ($esAdmin) : ?>
                        <button type="button" class="btn btn-warning" id="editEvent">Editar</button>
                        <button type="button" class="btn btn-danger" id="deleteEvent">Eliminar</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Imprimir QR -->
    <div class="modal fade" id="printQrModal" tabindex="-1" aria-labelledby="printQrModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printQrModalLabel">Imprimir QR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img src="generatePrintQR.php" alt="QR Code" class="img-fluid">
                    <br>
                    <br>
                    <a href="generatePrintQR.php" class="btn btn-primary">Imprimir QR</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Imprimir Registros-->
    <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printModalLabel">Imprimir Registros</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if ($hayRegistros) : ?>
                        <form id="printForm">
                            <div class="form-group">
                                <label for="startDate">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="startDate" name="startDate" required>
                            </div>
                            <div class="form-group">
                                <label for="endDate">Fecha de Fin</label>
                                <input type="date" class="form-control" id="endDate" name="endDate" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Imprimir</button>
                        </form>
                    <?php else : ?>
                        <div class="alert alert-warning" role="alert">
                            No hay registros disponibles para imprimir. Los registros solo se mantienen durante 7 días.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'get_oldest_date.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
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
                error: function(error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });

            $('#printForm').on('submit', function(e) {
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
                    success: function(response) {
                        if (response.pdf) {
                            var pdfUrl = response.pdf;
                            var win = window.open(pdfUrl, '_blank');
                            if (win) {
                                // Intentar eliminar el PDF después de un tiempo
                                setTimeout(function() {
                                    deletePDF(pdfUrl);
                                }, 10000); // Espera 5 segundos antes de intentar eliminar

                                // También intenta eliminar cuando la ventana se cierre
                                $(window).on('focus', function() {
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
                    error: function(error) {
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
                    success: function(deleteResponse) {
                        console.log('Intento de eliminación del PDF completado');
                    },
                    error: function(deleteError) {
                        console.error('Error al eliminar el PDF:', deleteError);
                    }
                });
            }
        });
    </script>

</body>

</html>