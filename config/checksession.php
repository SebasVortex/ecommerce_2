<?php
// Inicia el buffer de salida para evitar el error de encabezado
ob_start();

// Configura la duración de la sesión en segundos
$session_lifetime = 1800; // 30 minutos en segundos

// Configura los parámetros de la cookie de la sesión antes de iniciar la sesión
ini_set('session.gc_maxlifetime', $session_lifetime);
session_set_cookie_params([
    'lifetime' => $session_lifetime,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']), // True si la conexión es segura
    'httponly' => true, // Para evitar que sea accesible mediante JavaScript
    'samesite' => 'Lax' // Para evitar ataques CSRF
]);

// Inicia la sesión después de configurar los parámetros
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica si la sesión ya tiene un tiempo de inicio
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_lifetime) {
    // Si la sesión ha expirado, destruye la sesión
    session_unset();
    session_destroy();
}

// Actualiza el tiempo de la última actividad
$_SESSION['last_activity'] = time();

// Limpia el buffer de salida después de configurar la sesión
ob_end_clean();
?>
