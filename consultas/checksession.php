<?php
// Iniciar sesión si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
$is_logged_in = isset($_SESSION['user_id']);
?>
