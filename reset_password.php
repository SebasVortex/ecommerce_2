<?php
require 'vendor/autoload.php';
session_start();
include('config/database.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar si el token es válido y no ha expirado
    try {
        $now = date('U');
        $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = :token AND expires > :now LIMIT 1");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':now', $now);
        $stmt->execute();
        $reset = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($reset) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $password = trim($_POST['password']);
                $confirm_password = trim($_POST['confirm_password']);

                if ($password === $confirm_password) {
                    // Encriptar la nueva contraseña
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Actualizar la contraseña del usuario
                    $stmt = $conn->prepare("UPDATE clientes SET password = :password WHERE email = :email");
                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->bindParam(':email', $reset['email']);
                    $stmt->execute();

                    // Eliminar el token de la base de datos
                    $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = :token");
                    $stmt->bindParam(':token', $token);
                    $stmt->execute();

                    $_SESSION['success'] = 'Tu contraseña ha sido actualizada con éxito.';
                    header('Location: reset_password.php');
                    exit();
                } else {
                    $_SESSION['error'] = 'Las contraseñas no coinciden.';
                }
            }
        } else {
            $_SESSION['error'] = 'El token es inválido o ha expirado.';
            header('Location: forgot_password.php');
            exit();
        }
    } catch (PDOException $e) {
        error_log('Error en la base de datos: ' . $e->getMessage()); // Registrar el error en el log
        $_SESSION['error'] = 'Error en la base de datos.';
        header('Location: forgot_password.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>
<?php include 'assets/includes/head.php'; ?>
<title>Restablecer Contraseña</title>
<style>
    body {
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }
    .container2 {
        padding: 5rem 0rem 8rem 0rem;
        display: flex;
        justify-content: center;
    }
    .text-light{
        color:#808080;
    }
    .container-inside {
        background-color: #ffffff;
        box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.3);
        max-width: 600px;
        padding: 65px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 30px;
    }
    .icon {
        width: 180px;
        margin-bottom: 20px;
    }
    .btn-log {
        background-color: #D10024;
        transition: background-color 0.3s, box-shadow 0.2s, transform 0.2s;
        box-shadow: 0 7px 11px #a0a0a0;
        border: none !important;
        color: #fff;
        border-radius: 35px;
        width: 70%;
        margin-top: 10px;
        height: 42px;
        font-size: 18px;
        font-weight: 600;
        align-self: center;
        padding: 0 !important;
    }
    .btn-log:hover {
        background-color: #B31920;
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        transition: 0.6s;
    }
    .message {
        padding: 1rem;
        border-radius: 5px;
        margin-bottom: 20px;
        font-size: 16px;
        width: 100%;
        text-align: center;
    }
    .message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .instructions {
        font-size: 14px;
        color: #616161;
        margin-bottom: 20px;
        text-align: center;
    }
    .input-container{
        display: flex;
        flex-direction: column;
        width: 70%;
        gap: 5px;
    }
    .input-container input {
        padding: 1rem;
        font-size: 18px;
        border: none;
        transition: border-color 0.3s, box-shadow 0.3s;
        border-bottom: 1px solid gray;
    }
    .input-container input:focus {
        border-color: #D10024;
        box-shadow: 0 0 8px rgba(209, 0, 36, 0.5);
        outline: none;
    }
    .error-message {
        color: #721c24;
        font-size: 14px;
        margin-top: 5px;
    }
</style>
</head>
<body>
    <?php include 'assets/includes/header.php'; ?>
    <div class="container2">
        <div class="container-inside">
            <img src="assets/images/lock_icon.png" alt="Icono de Seguridad" class="icon">
            <h2>Restablecer Contraseña</h2>
            <p class="instructions">Ingresa tu nueva contraseña y confírmala para completar el proceso de restablecimiento.</p>
            <?php
            if (isset($_SESSION['error'])) {
                $errorMessage = $_SESSION['error'];
                unset($_SESSION['error']);
                echo '<p class="message error">' . htmlspecialchars($errorMessage) . '</p>';
            }
            if (isset($_SESSION['success'])) {
                $successMessage = $_SESSION['success'];
                unset($_SESSION['success']);
                echo '<p class="message success">' . htmlspecialchars($successMessage) . '</p>';
            }
            ?>
            <form action="" method="post" class="input-container">
                <input type="password" name="password" placeholder="Nueva contraseña" required>
                <span class="error-message" id="password-error"></span>
                <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
                <span class="error-message" id="confirm_password-error"></span>
                <input type="submit" value="Actualizar Contraseña" class="btn-log">
            </form>
        </div>
    </div>
    <?php include 'assets/includes/footer.php'; ?>
</body>
</html>
