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
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $tipo_usuario = trim($_POST['tipo_usuario']);

    // Limpieza y validación de entradas
    function sanitizeInput($input) {
        return htmlspecialchars(strip_tags($input));
    }

    $username = sanitizeInput($username);
    $email = sanitizeInput($email);
    $password = sanitizeInput($password);
    $nombre = sanitizeInput($nombre);
    $apellido = sanitizeInput($apellido);
    $tipo_usuario = sanitizeInput($tipo_usuario);

    try {
        $stmt = $conn->prepare("SELECT id FROM clientes WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = 'El nombre de usuario o el email ya están en uso.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO clientes (username, email, password, nombre, apellido, tipo_usuario) VALUES (:username, :email, :password, :nombre, :apellido, :tipo_usuario)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':tipo_usuario', $tipo_usuario);
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
        .animated.fast {
            font-family: inherit;
        }
       .container-reg {
            padding: 5rem 0rem 8rem 0rem;
            display: flex;
            justify-content: center;
            height: 800px;
        }
        .register-container {
            display: flex;
            background-color: #ffffff;
            box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.3);
            max-width: 900px;
        }
        .btn-reg {
            background-color: #D10024;
            transition: background-color 0.3s, box-shadow 0.2s; 
            box-shadow: 0 7px 11px #a0a0a0;
            border: none;
            color: #fff;
            border-radius: 35px;
            width: 50%;
            margin-top: 55px;
            height: 50px;
            font-size: 18px;
            font-weight: 600;
        }
        .btn-reg:hover {
            background-color: #B31920;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }
        .btn-reg:focus, .btn-reg:active {
            outline: none;
            background-color: #8E171C;
        }
        .img-reg {
            width: 50%;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .img-reg::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('assets/images/register.png');
            background-size: cover;
            background-position: center;
            filter: blur(5px);
            z-index: 1;
        }
        .img-int {
            position: relative;
            z-index: 2;
            text-align: center;
        }
        .img-int img {
            width: 50%;
        }
        .form-reg {
            width: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            gap: 40px;
            padding: 35px;
        }
        h2 {
            margin-top: 20px;
            margin-bottom: 20px;
            font-weight: 400;
            color: #616161;
        }
        .password-container {
            width: 100%;
            display: flex;
            align-items: flex-end;
            margin-top: 25px;
            gap: 20px;
            color: #616161;
        }
        .password-input {
            width: 100%;
            padding: 1rem;
            font-size: 18px;
            background-color: transparent;
            border: none;
            border-bottom: 1px solid #ccc;
        }
        .password-input:focus {
            outline: none;
        }
        .show-password {
            cursor: pointer;
        }
        .register-user {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .password-container span img {
            width: 35px;
            margin-left: 15px;
        }
        span.register {
            text-decoration: underline;
            color: #D10024;
            font-weight: 500;
        }
        .nya-cont{
            gap: 15px;
        }
        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
                box-shadow: none;
                background-color: transparent;
            }
            .img-reg {
                width: 100%;
                height: 200px;
            }
            .form-reg {
                width: 100%;
                padding: 20px;
            }
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

    <div class="container-reg">
        <div class="register-container">
            <div class="img-reg">
                <div class="img-int">
                    <img src="assets/images/logo-login.png" alt="Logo">
                </div>
            </div>
            <div class="form-reg">
                <form action="register.php" method="post" class="register-user">
                    <h2>Crear Cuenta</h2>
                    <div class="password-container nya-cont">
                        <input type="text" class="password-input" id="nombre" name="nombre" placeholder="Nombre" required>
                        <input type="text" class="password-input" id="apellido" name="apellido" placeholder="Apellido" required>
                        <span><i class="fa-solid fa-id-card" style="font-size:26px"></i></span>
                    </div>
                    <div class="password-container">
                        <input type="text" class="password-input" id="username" name="username" placeholder="Nombre de Usuario" required>
                        <span><i class="fa-solid fa-user" style="font-size:26px"></i></span>
                    </div>
                    <div class="password-container nya-cont">
                        <div style="width: 100%;">
                            <select class="password-input" id="tipo_usuario" name="tipo_usuario" required>
                                <option value="">Selecciona tipo de Perfil</option>
                                <option value="empresa">Empresa</option>
                                <option value="consumidor_final">Consumidor Final</option>
                            </select>
                            <p style="font-size: 12px; color: #616161; margin-top: 5px;">Esta opción no se podrá cambiar más adelante.*</p>
                        </div>
                        <span><i class="fa-solid fa-building-user" style="font-size:26px; margin-bottom:30px;"></i></span>
                    </div>
                    <div class="password-container" style="margin-top:5px;">
                        <input type="email" class="password-input" id="email" name="email" placeholder="Email" required>
                        <span><i class="fa-solid fa-envelope" style="font-size:26px"></i></span>
                    </div>
                    <div class="password-container">
                        <input type="password" class="password-input" id="password" name="password" placeholder="Contraseña" required>
                        <span class="show-password" onclick="togglePassword()"><i id="passwordIcon" class="fa-solid fa-lock" style="font-size:26px"></i></span>
                    </div>
                    
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <button type="submit" class="btn-reg">Registrarme</button>
                </form>
                <p><a href="login.php">¿Ya tenés una cuenta?<span class="register"> Iniciar sesión</span></a></p>
            </div>
        </div>
    </div>
    <!-- PIE DE PÁGINA -->
    <?php include 'assets/includes/footer.php';?>
    <!-- /PIE DE PÁGINA -->
    <script>
    function togglePassword() {
        var passwordField = document.getElementById('password');
        var passwordIcon = document.getElementById('passwordIcon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordIcon.classList.remove('fa-lock');
            passwordIcon.classList.add('fa-unlock');
        } else {
            passwordField.type = 'password';
            passwordIcon.classList.remove('fa-unlock');
            passwordIcon.classList.add('fa-lock');
        }
    }
</script>
</body>
</html>
