<?php
session_start();

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar el token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = 'Token CSRF inválido.';
        header('Location: register.php');
        exit();
    }

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Limpieza y validación de entradas
    function sanitizeInput($input) {
        return htmlspecialchars(strip_tags($input));
    }

    $username = sanitizeInput($username);
    $email = sanitizeInput($email);
    $password = sanitizeInput($password);

    try {
        $stmt = $conn->prepare("SELECT id FROM clientes WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = 'El nombre de usuario o el email ya están en uso.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO clientes (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Registro exitoso. Puedes iniciar sesión.';
                header('Location: login.php');
                exit();
            } else {
                $_SESSION['error'] = 'Error al registrar el usuario.';
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error de base de datos: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'assets/includes/head.php';?>
    <title>Registro</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .register-header {
            margin-bottom: 20px;
        }
        .register-header h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .register-footer {
            margin-top: 20px;
        }
        .register-footer p {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <?php include 'assets/includes/header.php';?>
    <!-- HEADER -->

    <?php
    if (isset($_SESSION['error'])) {
        echo '<p>' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    }
    ?>

    <div class="container">
        <div class="register-container">
            <div class="register-header text-center">
                <h2>Registro</h2>
            </div>
            <form action="register.php" method="post">
                <div class="form-group">
                    <label for="username">Nombre de Usuario:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <button type="submit" class="btn btn-primary btn-block">Registrar</button>
            </form>
            <div class="register-footer text-center">
                <p><a href="login.php">Ya tengo una cuenta. Iniciar sesión</a></p>
            </div>
        </div>
    </div>
    <!-- PIE DE PÁGINA -->
    <?php include 'assets/includes/footer.php';?>
    <!-- /PIE DE PÁGINA -->
</body>
</html>
