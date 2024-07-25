<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="icon" href="img\logo.svg" type="image/svg+xml">
    <style>
        body {
            background: rgb(161, 192, 220);
        }
    </style>
</head>

<body>
    <nav class="navbar bg-body-tertiary" style="background:#635992;">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="index.php">
                    <img src="img/logo.svg" alt="Logo" width="65" height="65" class="d-inline-block align-text-top">
                </a>
                <h1 class="navbar-text text-center font-italic mb-0" style="color: rgb(39, 23, 111); margin-left: 15px;">REGISTRO</h1>
            </div>
        </div>
    </nav>
    <br>
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <form action="registro_procesar.php" method="POST">

                    <input type="hidden" name="accion" id="exampleFormControlInput1" value="alta">

                    <div class="form-group">
                        <label for="exampleFormControlInput1" style="color:white;" class="font-weight-bold">Usuario</label>
                        <input type="text" name="usuario" class="form-control" id="exampleFormControlInput1" placeholder="Ingrese su Usuario" required>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1" style="color:white;" class="font-weight-bold">Clave</label>
                        <input type="password" name="clave" class="form-control" id="exampleFormControlInput1" placeholder="Ingrese su Clave" required>
                    </div>

                    <div class="form-group">
                        <label for="exampleFormControlInput1" style="color:white;" class="font-weight-bold">Nombre y Apellido</label>
                        <input type="text" name="nomyapp" class="form-control" id="exampleFormControlInput1" placeholder="Ingrese su Nombre y Apellido" required>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="bi bi-person-plus me-2"></i> Registrarse
                        </button>
                    </div>


                </form>

                <div class="text-center">
                    <a class="btn btn-link" href="iniciar_sesion.php" role="button" style="color:white;">¿Ya tienes cuenta? Inicia sesión</a>
                </div>

            </div>
        </div>
    </div>
    <br>
    <br>

    <?php
    include('footer.php');
    ?>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
</body>