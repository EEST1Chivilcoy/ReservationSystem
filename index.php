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

    $fecha_actual = date('Y/m/d');

    // Construir la consulta con filtro de fecha y formateo de fecha
    $consulta_ordenada = "
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
        <div class="card" style="background:#696969">
            <img src="img/audio_visuales.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h3 class="card-title" style="color:white; text-align:center;">AUDIOVISUALES</h3>
                <p class="card-text">Esta sala tiene una capacidad máxima de 30 personas.
                    Se recomienda reservar con amplia anticipación.</p>
            </div>
        </div>
        <div class="card" style="background:#545454">
            <img src="img/comedor.jpg" class="card-img-top" alt="...">
            <div class="card-body">
                <h3 class="card-title" style="color:white; text-align:center;">COMEDOR</h3>
                <p class="card-text">Esta sala tiene capacidad aproximada de 50 personas o más.
                    Pueden asistir grupos de Aula individualmente o en conjunto.</p>
            </div>
        </div>
        <div class="card" style="background:#696969">
            <img src="img/actos.jpg" class="card-img-top" alt="...">
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
                                        echo "<td>
            <a href='reserva/modifica_reserva.php?id=" . $d['ID'] . "&nombreapellido=" . urlencode($d['nombreapellido']) .
                                            "&curso=" . urlencode($d['curso']) . "&materia=" . urlencode($d['materia']) .
                                            "&horario=" . urlencode($d['horario']) . "&horario1=" . urlencode($d['horario1']) . "&fecha=" . urlencode($d['fecha']) .
                                            "&info=" . urlencode($d['info']) . "&materiales=" . urlencode($d['materiales']) . "'
            >Editar</a>
            |
            <a href='reserva\baja_reserva.php?id=" . $d['ID'] . "&nombreapellido=" . urlencode($d['nombreapellido']) .
                                            "&curso=" . urlencode($d['curso']) . "&materia=" . urlencode($d['materia']) .
                                            "&horario=" . urlencode($d['horario']) . "&horario1=" . urlencode($d['horario1']) . "&fecha=" . urlencode($d['fecha']) .
                                            "&info=" . urlencode($d['info']) . "&materiales=" . urlencode($d['materiales']) . "'
            >Eliminar</a>
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

    <!-- Modal -->
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