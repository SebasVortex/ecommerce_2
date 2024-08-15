<?php
session_start();
include 'config/database.php'; // Asegúrate de que este archivo maneje la conexión a la base de datos con PDO

// Verificar el token CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = 'Token CSRF inválido.';
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Limpieza y validación de entradas
    function sanitizeInput($input) {
        return htmlspecialchars(strip_tags($input));
    }

    $username = sanitizeInput($username);
    $password = sanitizeInput($password);

    // Consulta para buscar al usuario por nombre de usuario o email
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE username = :username OR email = :email LIMIT 1");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $username); // Usar el mismo parámetro para username y email
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica si el usuario existe y la contraseña es correcta
    if ($user && password_verify($password, $user['password'])) {
        // Guarda el ID del usuario en la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Redirige a la página principal u otra página que desees
        header('Location: index.php');
        exit();
    } else {
        // Guarda un mensaje de error en la sesión
        $_SESSION['error'] = 'Nombre de usuario, email o contraseña incorrectos.';
        header('Location: login.php');
        exit();
    }
}
?>
