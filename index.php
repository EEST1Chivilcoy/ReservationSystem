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
    <link rel="icon" href="img\logo.svg" type="image/svg+xml">

    <!-- jQuery y Popper.js (requeridos para Bootstrap JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Moment.js (para manejar fechas) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

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
    $items_per_page = 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $items_per_page;

    // Contar el total de elementos
    $query = "SELECT COUNT(*) as total FROM tabla";
    $result = mysqli_query($conexion, $query);
    $total_items = mysqli_fetch_assoc($result)['total'];
    $total_pages = ceil($total_items / $items_per_page);

    // Obtener los datos para la página actual
    $consulta_ordenada = "SELECT *, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha_formateada 
    FROM tabla 
    WHERE fecha >= '$fecha_actual'
    ORDER BY ABS(DATEDIFF(fecha, '$fecha_actual')) ASC
    LIMIT $offset, $items_per_page
    ";

    // Construir la consulta con filtro de fecha y formateo de fecha
    $consulta_ordenada_antigua = "
    SELECT *, DATE_FORMAT(fecha, '%d/%m/%Y') AS fecha_formateada 
    FROM tabla 
    WHERE fecha >= '$fecha_actual'
    ORDER BY ABS(DATEDIFF(fecha, '$fecha_actual')) ASC
    ";

    $consulta_borrar = "DELETE FROM tabla WHERE fecha < DATE_SUB('" . $fecha_actual . "', INTERVAL 7 DAY)"; //Ahora esta consulta borra despues de 7 Dias (Una semana)
    mysqli_query($conexion, $consulta_borrar) or die('Error en consulta de fechas');

    $resultado = mysqli_query($conexion, $consulta_ordenada) or die('Error en consulta');
    $num_filas = mysqli_num_rows($resultado);

    mysqli_close($conexion);
    ?>
    <style>
        body {
            background: rgb(161, 192, 220);
        }

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
    </style>
</head>

<body>
    <nav class="navbar bg-body-tertiary" style="background:#635992;">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="#">
                    <img src="img\logo.svg" alt="Logo" width="65" height="65" class="d-inline-block align-text-top">
                </a>
                <h1 class="navbar-text text-center font-italic mb-0" style="color: rgb(39, 23, 111); margin-left: 15px;">RESERVA DE SALÓN AUDIOVISUAL</h1>
            </div>
        </div>
    </nav>

    <br>
    <h3 class="text-center font-italic" style="color: white">INFO</h3>
    <br>
    </div>
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
    </div><br>
    <!-- Fila 2 (Tabla)-->
    <div class="container">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">
                <?php if ($num_filas > 0) : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-hover table-dark table-striped">
                            <thead style="background-color: #003333;">
                                <tr style="color: white;" class="text-center font-italic">
                                    <td>Nombre y Apellido</td>
                                    <td>Curso</td>
                                    <td>Materia</td>
                                    <td>Horario</td>
                                    <td>Hasta</td>
                                    <td>Fecha</td>
                                    <td>Salón</td>
                                    <td>Materiales</td>
                                    <?php if ($esAdmin) : ?>
                                        <td>Opciones</td>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($d = mysqli_fetch_array($resultado)) {
                                    echo "<tr>";
                                    echo "<td>" . $d['nombreapellido'] . "</td>";
                                    echo "<td>" . $d['curso'] . "</td>";
                                    echo "<td>" . $d['materia'] . "</td>";
                                    echo "<td>" . $d['horario'] . "</td>";
                                    echo "<td>" . $d['horario1'] . "</td>";
                                    echo "<td>" . $d['fecha_formateada'] . "</td>";
                                    echo "<td>" . $d['info'] . "</td>";
                                    echo "<td>" . $d['materiales'] . "</td>";
                                    if ($esAdmin) {
                                        echo "<td class='actions'>
                                        <a href='reserva/modifica_reserva.php?id=" . $d['ID'] . "&nombreapellido=" . urlencode($d['nombreapellido']) .
                                            "&curso=" . urlencode($d['curso']) . "&materia=" . urlencode($d['materia']) .
                                            "&horario=" . urlencode($d['horario']) . "&horario1=" . urlencode($d['horario1']) . "&fecha=" . urlencode($d['fecha']) .
                                            "&info=" . urlencode($d['info']) . "&materiales=" . urlencode($d['materiales']) . "' class='btn btn-link'>
                                        <i class='bi bi-pencil'></i> <!-- Editar icon -->
                                        </a>
                                        
                                        <i class='bi bi-dash'></i>

                                        <a href='#' class='btn btn-link' onclick=\"openDeleteModal('{$d['ID']}', '" . urlencode($d['nombreapellido']) . "', '" . urlencode($d['curso']) . "', '" . urlencode($d['materia']) . "', '" . urlencode($d['horario']) . "', '" . urlencode($d['horario1']) . "', '" . urlencode($d['fecha']) . "', '" . urlencode($d['info']) . "', '" . urlencode($d['materiales']) . "')\">
                                            <i class='bi bi-trash'></i> <!-- Borrar icon -->
                                        </a>

                                        </td>";
                                    }
                                    echo "</tr>";
                                }
                                ?>

                            </tbody>
                        </table>
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

                <!-- Paginación -->
                <?php if ($total_pages > 1) : ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-end me-3">
                        <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                            <a class="page-link bg-dark text-light border-dark" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                <a class="page-link bg-dark text-light border-dark" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $page == $total_pages ? 'disabled' : '' ?>">
                            <a class="page-link bg-dark text-light border-dark" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>

            </div>
            <div class="col-1"></div>
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

    <!-- Modal Borrar Reserva -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn btn-link text-white p-0 m-0" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro que deseas eliminar esta reserva?
                    <div id="modalContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a id="confirmDelete" href="#" class="btn btn-danger">Eliminar</a>
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
        function openDeleteModal(id, nombreapellido, curso, materia, horario, horario1, fecha, info, materiales) {
            // Decodificar los valores y reemplazar '+' con espacios
            nombreapellido = decodeURIComponent(nombreapellido.replace(/\+/g, ' '));
            curso = decodeURIComponent(curso.replace(/\+/g, ' '));
            materia = decodeURIComponent(materia.replace(/\+/g, ' '));
            horario = decodeURIComponent(horario.replace(/\+/g, ' '));
            horario1 = decodeURIComponent(horario1.replace(/\+/g, ' '));
            fecha = decodeURIComponent(fecha.replace(/\+/g, ' '));
            info = decodeURIComponent(info.replace(/\+/g, ' '));
            materiales = decodeURIComponent(materiales.replace(/\+/g, ' '));

            // Formatear la fecha de AAAA-MM-DD a DD/MM/AAAA
            let fechaFormateada = fecha.split('-').reverse().join('/');

            // Aquí puedes actualizar el contenido del modal si es necesario
            document.getElementById('modalContent').innerHTML = `
            <br>
            <p><strong>Nombre:</strong> ${nombreapellido}</p>
            <p><strong>Curso:</strong> ${curso}</p>
            <p><strong>Materia:</strong> ${materia}</p>
            <p><strong>Horario:</strong> ${horario} - ${horario1}</p>
            <p><strong>Fecha:</strong> ${fechaFormateada}</p>
            <p><strong>Información:</strong> ${info}</p>
            <p><strong>Materiales:</strong> ${materiales}</p>
            `;

            // Actualizar el enlace de confirmación para que apunte al script de eliminación
            document.getElementById('confirmDelete').href = `reserva/baja_sql.php?id=${id}`;

            // Mostrar el modal
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

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