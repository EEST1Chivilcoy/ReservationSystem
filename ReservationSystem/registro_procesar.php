<?php
include('include/conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["usuario"])) {
        $usuario_error = "Por favor indique un usuario por favor";
    } else {
        $usuario = htmlspecialchars($_POST["usuario"], ENT_QUOTES, 'UTF-8');
    }

    if (empty($_POST["clave"])) {
        $clave_error = "Por favor cargue una clave para el usuario.";
    } else {
        $clave = htmlspecialchars($_POST["clave"], ENT_QUOTES, 'UTF-8');
    }

    if (empty($_POST["nomyapp"])) {
        $nomyapp_error = "Por favor ingrese el Nombre y Apellido para el usuario";
    } else {
        $nomyapp = htmlspecialchars($_POST["nomyapp"], ENT_QUOTES, 'UTF-8');
    }

    if ($_POST["accion"] == "alta") {
        if (!empty($usuario_error) || !empty($clave_error)) {
            $Message = "Debe completar los campos obligatorios";
            header("Location:registrarse.php?error={$Message}");
        } else {
            $stmt = $conexion->prepare("SELECT COUNT(usuario) AS nuevo FROM usuarios WHERE usuario = ?");
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $stmt->bind_result($existe);
            $stmt->fetch();
            $stmt->close();

            if ($existe) {
                $Message = "El usuario ya existe, elija otro usuario";
                header("Location:registrarse.php?error={$Message}");
                exit;
            }

            // Encriptar la contraseña
            $clavehash = password_hash($clave, PASSWORD_BCRYPT);
            $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, clave, NombreYApellido) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $usuario, $clavehash, $nomyapp);

            if ($stmt->execute()) {
                $Message = "Se ha creado el usuario " . $usuario;
                header("Location:iniciar_sesion.php?success={$Message}");
            } else {
                $Message = "Error al crear el usuario";
                header("Location:registrarse.php?error={$Message}");
            }
            $stmt->close();
        }
    }
} else {
    header("Location:error.html");
}
