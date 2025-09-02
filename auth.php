<?php
// auth.php

// Verificar si la sesión no ha sido iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function verificarRol($rolPermitido) {
    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== TRUE) {
        // Redirigir al inicio de sesión si no está autenticado
        header('Location: index.php');
        exit;
    }

    // Verificar el rol del usuario
    if ($_SESSION['roles'] !== $rolPermitido) {
        // Redirigir a la página de acceso denegado si no tiene el rol permitido
        header('Location: accesoDenegado.php');
        exit;
    }
}
?>

