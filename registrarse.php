<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="icon" href="img/logo.svg" type="image/svg+xml">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            background: rgb(161, 192, 220);
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .btn-block {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            color: white;
        }

        .btn-success {
            border: none;
            font-size: 16px;
        }

        .form-group label {
            color: #333;
        }

        .text-center a {
            color: #007bff;
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <nav class="navbar bg-body-tertiary" style="background:#635992;">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="index.php">
                    <img src="img\logo.svg" alt="Logo" width="65" height="65" class="d-inline-block align-text-top">
                </a>
                <h1 class="navbar-text text-center font-italic mb-0" style="color: rgb(39, 23, 111); margin-left: 15px;">REGISTRO</h1>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="container">
            <form class="mt-4" action="registro_procesar.php" method="POST">

                <input type="hidden" name="accion" id="exampleFormControlInput1" value="alta">

                <div class="form-group mb-3">
                    <label for="usuario" class="font-weight-bold">Usuario</label>
                    <input type="text" class="form-control" id="usuario" placeholder="Usuario" name="usuario" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="font-weight-bold">Clave</label>
                    <input type="password" class="form-control" id="password" placeholder="Clave" name="clave" required>
                </div>
                <div class="form-group mb-4">
                    <label for="exampleFormControlInput1" class="font-weight-bold">Nombre y Apellido</label>
                    <input type="text" name="nomyapp" class="form-control" id="exampleFormControlInput1" placeholder="Ingrese su Nombre y Apellido" required>
                </div>
                <div class="d-grid gap-2 mb-3">
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="bi bi-person-plus me-2"></i> Registrarse
                    </button>
                </div>
            </form>
            <div class="text-center">
                <a class="btn btn-link" href="iniciar_sesion.php" role="button">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>

</html>