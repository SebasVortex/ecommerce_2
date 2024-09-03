<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include('config/database.php');

// Inicializar las variables de mensaje
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    // Verificar si el correo electrónico existe en la base de datos
    try {
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generar un token único
            $token = bin2hex(random_bytes(50));
            $expires = date('U') + 1800; // 30 minutos de validez

            // Guardar el token en la base de datos
            $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires) VALUES (:email, :token, :expires)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expires', $expires);
            $stmt->execute();

            // Enviar el correo electrónico con el enlace de restablecimiento
            $resetLink = 'http://sistemasenergeticos.com.ar/reset_password.php?token=' . $token;
            
            // Configuración del PHPMailer
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Cambia esto por tu servidor SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'sitioweb.sesa@gmail.com'; // Tu dirección de correo
                $mail->Password = 'gggezcxwbmutcoix'; // La contraseña de tu cuenta de correo
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('sitioweb.sesa@gmail.com', 'Sistema de Gestión');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Restablecimiento de contraseña';
                $mail->Body = 'Hacé clic en el siguiente enlace para restablecer tu contraseña: <a href="' . $resetLink . '">' . $resetLink . '</a>';

                $mail->send();
                $successMessage = 'Te hemos enviado un correo electrónico con las instrucciones para restablecer tu contraseña.';
            } catch (Exception $e) {
                $errorMessage = 'Hubo un error enviando el correo electrónico. Error: ' . $mail->ErrorInfo;
            }
        } else {
            $errorMessage = 'El correo electrónico no está registrado.';
        }
    } catch (PDOException $e) {
        $errorMessage = 'Error en la base de datos.';
    }
}

// Limpia los mensajes de la sesión
unset($_SESSION['error']);
unset($_SESSION['success']);
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
            if ($errorMessage) {
                echo '<p>' . $errorMessage . '</p>';
            }
            if ($successMessage) {
                echo '<p>' . $successMessage . '</p>';
            }
            ?>
            <form action="forgot_password.php" method="post" class="input-container">
                <input type="email" name="email" placeholder="Ingresa tu correo electrónico" required>
                <input type="submit" value="Enviar" class="btn-log">
            </form>
        </div>
    </div>
    <?php include 'assets/includes/footer.php'; ?>
</body>
</html>
