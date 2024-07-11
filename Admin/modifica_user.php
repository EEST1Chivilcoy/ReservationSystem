<?php
require '../include/VerificacionAdmin.php'
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuario</title>
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
    $usuario = $_GET['usuario'];
    $nombreapellido = $_GET['NombreYApellido'];
    $esAdmin = $_GET['esAdmin'];
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
                <h1 class="navbar-text text-center font-italic mb-0" style="color: rgb(39, 23, 111); margin-left: 15px;">MODIFICAR USUARIO</h1>
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

                <form action="modifica_sql.php" method="post">

                    <input type="hidden" id="ID" name="ID" value="<?php echo $id_tabla; ?>">

                    <div class="form-group">
                        <label for="usuario" class="font-weight-bold">Usuario</label>
                        <input type="text" id="usuario" name="usuario" value="<?php echo $usuario; ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="nombreyapellido" class="font-weight-bold">Nombre y Apellido</label>
                        <input type="text" id="nombreyapellido" name="nombreyapellido" value="<?php echo $nombreapellido; ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="password" class="font-weight-bold">Contraseña</label>
                        <input type="password" id="contraseña" name="contraseña" value="********" class="form-control">
                    </div>

                    <?php if ($esAdmin) : ?>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="esAdmin" name="esAdmin" value=1 checked>
                                <label class="font-weight-bold custom-control-label" for="esAdmin" style="color:white">Administrador</label>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="esAdmin" name="esAdmin" value=0>
                                <label class="font-weight-bold custom-control-label" for="esAdmin" style="color:white">Administrador</label>
                            </div>
                        </div>
                    <?php endif; ?>


                    <button type="submit" class="btn btn-danger btn-block" name="boton" value=1>Modificar usuario</button>
                    <button type="submit" class="btn btn-primary btn-block" name="boton" value=0>Anular la modificacion</button>
                </form>

            </div>

            <div class="col-3"></div>

        </div>

    </div>
    <br>
    <br>

    <?php
    include('../footer.php');
    ?>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
</body>

</html>