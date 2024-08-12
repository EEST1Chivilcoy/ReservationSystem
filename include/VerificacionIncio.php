<?php
session_start();

if (!isset($_SESSION['loggedIn'])) {
    header('Location: iniciar_sesion.php');
    exit();
}

?>