<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="icon" href="img\logo.svg" type="image/svg+xml">
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
    <nav class="navbar bg-body-tertiary" style="background:#635992;">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="index.php">
                    <img src="img/logo.svg" alt="Logo" width="65" height="65" class="d-inline-block align-text-top">
                </a>
                <h1 class="navbar-text text-center font-italic mb-0" style="color: rgb(39, 23, 111); margin-left: 15px;">ACCEDER</h1>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form class="mt-5" aria-labelledby="dropdownMenuOffset" action="login.php" method="POST">
                    <div class="form-group">
                        <label for="exampleDropdownFormEmail2" style="color:white;" class="font-weight-bold">Usuario</label>
                        <input type="text" class="form-control" id="usuario" placeholder="Usuario" name="username">
                    </div>
                    <div class="form-group">
                        <label for="exampleDropdownFormPassword2" style="color:white;" class="font-weight-bold">Clave</label>
                        <input type="password" class="form-control" id="password" placeholder="Clave" name="password">
                    </div>
                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-info btn-block">Ingresar</button>
                    </div>
                </form>
                <div class="text-center">
                    <a class="btn btn-link" href="registrarse.php" role="button" style="color:white;">¿Aún no tienes cuenta? Regístrate</a>
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

</html>