<?php
session_start();

if (!isset($_SESSION['loggedIn'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['EsAdmin']) || $_SESSION['EsAdmin'] !== true) {
    echo "<script>alert('Tienes que ser administrador para acceder a esta página.'); window.location.href = 'index.php';</script>";
    exit();
}
?>
