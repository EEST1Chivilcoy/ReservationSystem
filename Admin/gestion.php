<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Usuarios</title>
    <link rel="stylesheet" href="..\css\bootstrap.min.css">
    <link rel="stylesheet" href="..\css\estilo.css">
    <link rel="icon" href="../img/logo.svg" type="image/svg+xml">

    <?php
    require "../include/VerificacionAdmin.php";
    include('../include/conexion.php');

    $consulta = "SELECT * FROM usuarios";

    $resultado = mysqli_query($conexion, $consulta) or die('Error en consulta');

    mysqli_close($conexion);
    ?>

    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            background: rgb(161, 192, 220);
            display: flex;
            flex-direction: column;
        }

        .container {
            flex: 1;
        }
    </style>

</head>

<body>
    <!-- Titulo de la pagina -->
    <nav class="navbar bg-body-tertiary" style="background:#635992;">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="../index.php">
                    <img src="../img/logo.svg" alt="Logo" width="65" height="65" class="d-inline-block align-text-top">
                </a>
                <h1 class="navbar-text text-center font-italic mb-0" style="color: rgb(39, 23, 111); margin-left: 15px;">GESTION DE USUARIOS</h1>
            </div>
        </div>
    </nav>
    <br>
    <br>
    <div class="titulo">
        <h1 class="text-center font-italic" style="color:white;">USUARIOS</h1>
    </div>
    <br>

    <div class="container">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-hover table-dark table-striped">
                        <thead style="background-color: #003333;">
                            <tr style="color: white;" class="text-center font-italic">
                                <td>Usuario</td>
                                <td>Contraseña</td>
                                <td>Nombre y Apellido</td>
                                <td>Opciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($d = mysqli_fetch_array($resultado)) {
                                echo "<tr class=\"text-center\">";
                                echo "<td>" . $d['usuario'] . "</td>";
                                echo "<td>" . "********" . "</td>";
                                echo "<td>" . $d['NombreYApellido'] . "</td>";

                                echo "<td>
                                    <a href='modifica_user.php?id=" . $d['ID'] . "&usuario=" . urlencode($d['usuario']) .
                                    "&NombreYApellido=" . urlencode($d['NombreYApellido']) . "&esAdmin=" . urlencode($d['esAdmin']) . "'
                                    >Editar</a>
                                    |
                                    <a href='borrar_user.php?id=" .  $d['ID'] . "&usuario=" . urlencode($d['usuario']) .
                                    "&NombreYApellido=" . urlencode($d['NombreYApellido']) . "&esAdmin=" . urlencode($d['esAdmin']) . "'
                                    >Eliminar</a>
                                    </td>";

                                echo "<tr>";
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-1"></div>
        </div>
    </div>

    <br>
    <br>

    <div class="d-flex justify-content-center">
        <a href="../index.php" class="btn btn-secondary btn-lg w-50 position-relative">
            <i class="bi bi-arrow-left me-2"></i> Volver al inicio
        </a>
    </div>


    <br>
    <br>

    <?php
    include('../footer.php');
    ?>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
</body>

</html>