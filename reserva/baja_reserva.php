<?php
require '../include/VerificacionAdmin.php'
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar reserva</title>
    <!-- CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="icon" href="../img/logo.svg" type="image/svg+xml">
    <style>
        body {
            background: rgb(161, 192, 220);
        }

        label {
            color: white;
        }
    </style>

    <!-- Archivos a incluir -->
    <?php
    $id_tabla = $_GET['id'];
    $usuario = $_GET['nombreapellido'];
    $curso = $_GET['curso'];
    $materia = $_GET['materia'];
    $horario = $_GET['horario'];
    $horario1 = $_GET['horario1'];
    $fecha = $_GET['fecha'];
    $info = $_GET['info'];
    $materiales = $_GET['materiales'];
    ?>
</head>

<body>
    <!-- Titulo de la pagina -->
    <nav class="navbar bg-body-tertiary" style="background:#635992;">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="../index.php">
                    <img src="../img/logo.svg" alt="Logo" width="65" height="65" class="d-inline-block align-text-top">
                </a>
                <h1 class="navbar-text text-center font-italic mb-0" style="color: rgb(39, 23, 111); margin-left: 15px;">ELIMINAR TURNO</h1>
            </div>
        </div>
    </nav>
    <br>
    <br>
    <!-- Formulario -->
    <div class="container">

        <div class="row">

            <div class="col-3"></div>

            <div class="col-6">

                <form action="baja_sql.php" method="post">

                    <input type="hidden" id="ID" name="ID" value="<?php echo $id_tabla; ?>">

                    <div class="form-group">
                        <label for="nombreapellido" class="font-weight-bold">Nombre y Apellido</label>
                        <input type="text" id="nombreapellido" name="nombreapellido" readonly value="<?php echo $usuario; ?>" class="form-control">
                    </div>

                    <div class="form-group">

                        <label for="curso" class="font-weight-bold">Curso</label>

                        <input type="text" id="curso" name="curso" readonly value="<?php echo $curso; ?>" class="form-control">

                    </div>

                    <div class="form-group">

                        <label for="materia" class="font-weight-bold">Materia</label>

                        <input type="text" id="materia" name="materia" readonly value="<?php echo $materia; ?>" class="form-control">

                    </div>

                    <div class="form-group">
                        <label for="horario" class="font-weight-bold">Horario (Inicio)</label>
                        <input type="time" id="horario" name="horario" readonly value="<?php echo $horario; ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="horario1" class="font-weight-bold">Horario (Fin)</label>
                        <input type="time" id="horario1" name="horario1" readonly value="<?php echo $horario1; ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="fecha" class="font-weight-bold">Fecha</label>
                        <input type="date" id="fecha" name="fecha" readonly value="<?php echo $fecha; ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="info" class="font-weight-bold">Salon</label>
                        <input type="text" id="info" name="info" readonly value="<?php echo $info; ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="info" class="font-weight-bold">Materiales</label>
                        <input type="text" id="info" name="info" readonly value="<?php echo $materiales; ?>" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-danger btn-block" name="boton" value=1>Eliminar la reserva</button>
                    <br>
                    <button type="submit" class="btn btn-primary btn-block" name="boton" value=0>Anular la baja</button>
                </form>

            </div>

            <div class="col-3"></div>

        </div>

    </div>
    <br>
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