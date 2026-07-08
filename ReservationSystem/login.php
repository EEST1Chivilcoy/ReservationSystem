<?php
include('include/conexion.php');
session_start(); // Inicia la sesión al principio del script

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Variables para los mensajes de error
    $username_err = "";
    $password_err = "";

    if (empty($username)) {
        $username_err = "Por favor ingrese su nombre de usuario.";
    }

    if (empty($password)) {
        $password_err = "Por favor ingrese su contraseña.";
    }

    if (!empty($username_err) || !empty($password_err)) {
        $Message = "Debe completar los campos usuario y/o clave";
        header("Location: index.php?error=" . urlencode($Message));
        exit(); // Asegura que el script se detenga después de redirigir
    } else {
        // Utiliza una consulta preparada para evitar inyección SQL
        $stmt = $conexion->prepare("SELECT ID, clave, NombreYApellido, esAdmin, telefono, tipo_telefono, modal_v2_visto, foto_perfil FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_usuario, $clavebdd, $nombreyapellido, $esAdmin, $telefono, $tipo_telefono, $modal_v2_visto, $foto_perfil);
            $stmt->fetch();

            if (password_verify($password, $clavebdd)) {
                $_SESSION["usuario_id"] = $id_usuario; // Guarda el ID para relacionarlo con reservas
                $_SESSION["usuario"] = $username;
                $_SESSION["nombreyapellido"] = $nombreyapellido; // Guarda el nombre y apellido en la sesión
                $_SESSION["telefono"] = $telefono;
                $_SESSION["tipo_telefono"] = $tipo_telefono;
                $_SESSION["modal_v2_visto"] = $modal_v2_visto;
                $_SESSION["foto_perfil"] = $foto_perfil;
                
                if ($esAdmin == 1) {
                    $_SESSION["EsAdmin"] = true;
                } else {
                    $_SESSION['EsAdmin'] = false;
                }
                $_SESSION["loggedIn"] = true;
                $Message = "Bienvenido " . $_SESSION["nombreyapellido"] . "";
                header("Location: index.php?success=" . urlencode($Message));
                exit(); // Asegura que el script se detenga después de redirigir
            } else {
                $Message = "Usuario y/o clave inválidos";
                header("Location: index.php?error=" . urlencode($Message));
                exit(); // Asegura que el script se detenga después de redirigir
            }
        } else {
            $Message = "Usuario no encontrado";
            header("Location: index.php?error=" . urlencode($Message));
            exit(); // Asegura que el script se detenga después de redirigir
        }
    }

    $stmt->close();
    mysqli_close($conexion);
}
