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
        $username = $user['username']; // Aquí obtienes el nombre de usuario
        
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
            $resetLink = 'http://sistemasenergeticos.com.ar/ecommerce/reset_password.php?token=' . $token;
            
            // Configuración del PHPMailer
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true;
                $mail->Username = 'sitioweb.sesa@gmail.com'; 
                $mail->Password = 'gggezcxwbmutcoix'; 
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('sitioweb.sesa@gmail.com', 'Sistemas Energeticos');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Restablece tu clave';
                $mail->Body = '
                                <!DOCTYPE html>
                                <html lang="es">
                                <head>
                                    <meta charset="UTF-8">
                                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                </head>
                                <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f4f4f4; padding: 20px 0;">
                                        <tr>
                                            <td align="center">
                                                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="text-align: center; background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 10px; padding: 20px;">
                                                    <tr>
                                                        <td align="center" style="padding-bottom: 20px;">
                                                            <h1 style="font-size: 26px; color: #333; margin: 0;">Restablece tu contraseña</h1>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color: #555; padding-bottom: 20px; ">
                                                            <h2 style="margin: 0;">Hola,</h2>
                                                            <p style="margin: 10px 0 20px 0; font-size: 16px;">Hacé clic en el siguiente enlace para restablecer tu contraseña:</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" style="padding-bottom: 35px;">
                                                            <a href="' . $resetLink . '" style="background-color: #D10024; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 16px;">Restablecer contraseña</a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding-bottom: 20px; font-size: 16px; color: #555;">
                                                            <p style="margin: 0;">Para que no lo olvides, tu nombre de usuario es: <b> ' . $username . ' </b></p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-size: 16px; color: #555; ">
                                                            <small style="margin: 0;">Si no solicitaste este cambio, puedes ignorar este correo.</small>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </body>
                                </html>
                                ';
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
    }
    .text-light{
        color:#808080;
        text-align: center;
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
    
    .btn-log:hover {
        background-color: #B31920;
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }
    h2 {
        font-weight: 400;
        color: #616161;
        text-align: center;
    }
    .input-container {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 30px;
    }
    .input-container input {
        padding: 1rem;
        font-size: 18px;
        border: none;
        border-bottom: 1px solid #ccc;
        transition: border-color 0.3s;
    }
    .input-container input:focus {
        border-color: #D10024;
        outline: none;
    }
    .btn-log {
        background-color: #D10024;
        transition: background-color 0.3s, box-shadow 0.2s;
        box-shadow: 0 7px 11px #a0a0a0;
        border: none !important;
        color: #fff;
        border-radius: 35px;
        width: 45%;
        margin-top: 10px;
        height: 42px;
        font-size: 18px;
        font-weight: 600;
        align-self: center;
        padding: 0 !important;
    }
    @media (max-width:500px){
    h2{
        font-size: 24px;
    }
    .container-inside {
        padding: 20px;
    }
    .btn-log {
        width: 55%;
        margin-top: 0px;
        margin-bottom: 12px;
    }
    .text-light{
        
    }
    .input-container input {
        font-size: 16px;
    }
    }
</style>
</head>
<body>
    <?php include 'assets/includes/header.php'; ?>
    <div class="container2">
        <div class="container-inside">
            <img src="assets/images/logo.webp" alt="Logo" style="width: 100px; ">
            <h2>Restablecer Contraseña</h2>
            <?php
            if ($errorMessage) {
                echo '<p style="color: red;">' . $errorMessage . '</p>';
            }
            if ($successMessage) {
                echo '<p style="color: green;">' . $successMessage . '</p>';
            }
            ?>
            <form action="forgot_password.php" method="post" class="input-container">
                <input type="email" name="email" placeholder="Ingresa tu correo electrónico" required>
                <p class="text-light">Si no recibes el correo electrónico en unos minutos, revisa tu carpeta de spam o intenta nuevamente.</p>
                <input type="submit" value="Enviar" class="btn-log">
            </form>
            <p class="text-light">¿Ya recuerdas tu contraseña? <a style="text-decoration:underline;" href="login.php">Inicia sesión aquí</a>.</p>
        </div>
    </div>
    <?php include 'assets/includes/footer.php'; ?>
</body>
</html>