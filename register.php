<?php
session_start();
include 'config/database.php';

// Función para validar y limpiar entradas de usuario
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags($input));
}

// Procesa el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpia y valida las entradas del usuario
    $username = sanitizeInput(trim($_POST['username']));
    $email = sanitizeInput(trim($_POST['email']));
    $password = sanitizeInput(trim($_POST['password']));

    // Verifica si el nombre de usuario o el email ya están registrados
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE username = :username OR email = :email LIMIT 1");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['error'] = 'El nombre de usuario o el email ya están registrados.';
        header('Location: register.php');
        exit();
    } else {
        // Hashea la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Inserta el nuevo usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO clientes (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        // Redirige al login
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="path_to_your_stylesheet.css"> <!-- Reemplaza con la ruta a tu archivo CSS -->
</head>
<body>
    <h2>Registro</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <form action="register.php" method="POST">
        <div>
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Registrarse</button>
    </form>
</body>
</html>
