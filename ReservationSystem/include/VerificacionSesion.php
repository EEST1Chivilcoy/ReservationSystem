<?php
session_start();

if (!isset($_SESSION['loggedIn'])) {
    echo "<script>alert('No has iniciado sesión.'); window.location.href = 'index.php';</script>";
    exit();
}
?>
