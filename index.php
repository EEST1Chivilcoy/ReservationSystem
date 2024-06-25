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
    <?php
    $fecha_actual = date('Y/m/d');
    include('include/conexion.php');
    $consulta_ordenada = "SELECT * FROM tabla ORDER BY ABS(DATEDIFF(fecha, '$fecha_actual')) ASC";
    $consulta_borrar = "DELETE FROM tabla WHERE fecha < DATE_SUB('" . $fecha_actual . "', INTERVAL 1 DAY)";
    mysqli_query($conexion, $consulta_borrar) or die('Error en consulta de fechas');

    $resultado = mysqli_query($conexion, $consulta_ordenada) or die('Error en consulta');

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
            <img src="img/audio visuales.jpg" class="card-img-top" alt="...">
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
            <div class="btn btn-primary btn-lg w-50 position-relative">
                <a href="reserva/cargar_reserva.php?tabla=" class="stretched-link" style="color:white; text-decoration: none;">Reservar</a>
            </div>
        </div>
        <br>
        <div class="d-flex justify-content-center">
            <a class="btn btn-danger btn-lg w-50 position-relative" href="logout.php">
                Cerrar sesión <i class="fas fa-user-times"></i>
            </a>
        </div>
    <?php else : ?>
        <br>
        <div class="d-flex justify-content-center">
            <a class="btn btn-dark btn-lg w-50 position-relative" href="iniciar_sesion.php">Iniciar Sesion / Registrarse</a>
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
                                echo "<td>" . $d['fecha'] . "</td>";
                                echo "<td>" . $d['info'] . "</td>";
                                echo "<td>" . $d['materiales'] . "</td>";
                                if ($esAdmin) {
                                    echo "<td>
            <a href='reserva/modifica_reserva.php?id=" . $d['ID'] . "&nombreapellido=" . urlencode($d['nombreapellido']) .
                                        "&curso=" . urlencode($d['curso']) . "&materia=" . urlencode($d['materia']) .
                                        "&horario=" . urlencode($d['horario']). "&horario1=" . urlencode($d['horario1']) . "&fecha=" . urlencode($d['fecha']) .
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
            </div>
            <div class="col-1"></div>
        </div>
    </div>
    <?php if ($esAdmin) : ?>
        <br>
        <div class="d-flex justify-content-center">
            <a href="Admin\gestion.php" class="btn btn-secondary btn-lg w-50 position-relative">Modificar Usuarios</a>
        </div>
    <?php endif; ?>
    <br>

    <footer>
        <div class="contenedor-footer">
            <div class="cont-foo">
                <h4>Telefono</h4>
                <p>
                    <a href="tel:02346431330" style="color: white;">
                        <i class="bi bi-telephone-fill"></i> 2346-431330
                    </a>
                </p>
            </div>
            <div class="cont-foo">
                <h4>Localidad</h4>
                <p>Chivilcoy, Buenos Aires</p>
            </div>
        </div>
        <div class="footer-bottom">
            <h3>&copy; 7to "B" 2024 | EEST N° 1 | Profesor: Sergio Caffaro</h3>
        </div>
    </footer>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
</body>

</html>