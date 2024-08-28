<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Configura la duración de la sesión en segundos
$session_lifetime = 1800; // Debe coincidir con el tiempo de expiración en el código de inicio de sesión

// Verifica si la sesión ya tiene un tiempo de inicio
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_lifetime) {
    // Si la sesión ha expirado, destruye la sesión y redirige al inicio de sesión
    session_unset();
    session_destroy();
    header("Location: https://sistemasenergeticos.com.ar/gestionadmin/loginad.php");
    exit();
}

// Actualiza el tiempo de la última actividad
$_SESSION['last_activity'] = time();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    // Si no está autenticado, redirige al inicio de sesión
    header("Location: https://sistemasenergeticos.com.ar/gestionadmin/loginad.php");
    exit();
}
?>
