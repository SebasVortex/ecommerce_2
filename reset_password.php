<?php
require 'vendor/autoload.php';

session_start();
include('config/database.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar si el token es válido y no ha expirado
    try {
        $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = :token AND expires > :now LIMIT 1");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':now', date('U'));
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
                    header('Location: login.php');
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
    /* Estilo para el formulario */
    body {
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }
    .container2 {
        padding: 5rem 0rem 8rem 0rem;
        display: flex;
        justify-content: center;
        height: 600px;
    }
    .container-inside {
        display: flex;
        background-color: #ffffff;
        box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.3);
        max-width: 600px;
        flex-direction: column;
        align-items: center;
        padding: 30px;
    }
    .btn-log {
        background-color: #D10024;
        transition: background-color 0.3s, box-shadow 0.2s;
        box-shadow: 0 7px 11px #a0a0a0;
        border: none;
        color: #fff;
        border-radius: 35px;
        width: 55%;
        margin-top: 35px;
        height: 50px;
        font-size: 18px;
        font-weight: 600;
    }
    .btn-log:hover {
        background-color: #B31920;
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }
    h2 {
        font-weight: 400;
        color: #616161;
    }
    .input-container {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .input-container input {
        padding: 1rem;
        font-size: 18px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>
</head>
<body>
    <?php include 'assets/includes/header.php'; ?>
    <div class="container2">
        <div class="container-inside">
            <h2>Restablecer Contraseña</h2>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<p>' . $_SESSION['error'] . '</p>';
            }
            if (isset($_SESSION['success'])) {
                echo '<p>' . $_SESSION['success'] . '</p>';
            }
            ?>
            <form action="" method="post" class="input-container">
                <input type="password" name="password" placeholder="Nueva contraseña" required>
                <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
                <input type="submit" value="Actualizar Contraseña" class="btn-log">
            </form>
        </div>
    </div>
    <?php include 'assets/includes/footer.php'; ?>
</body>
</html>
