<?php 
session_start();

// Verificar el token CSRF
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = 'Token CSRF inválido.';
        header('Location: login.php?error=tokenmalo');
        exit();
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Limpieza y validación de entradas
    function sanitizeInput($input) {
        return htmlspecialchars(strip_tags($input));
    }

    $username = sanitizeInput($username);
    $password = sanitizeInput($password);

    // Buscar el usuario en la base de datos
    try {
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE username = :username OR email = :email LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar la contraseña
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error'] = 'Nombre de usuario, email o contraseña incorrectos.';
            header('Location: login.php?error=loginfailed');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error en la base de datos.';
        header('Location: login.php?error=dberror');
        exit();
    }
}

// Asegúrate de que la variable de error se borre al cargar la página
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}
?>

<?php include 'assets/includes/head.php';?>
    <title>Login - Iniciar Sesión</title>
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
            height: 800px;
        }
        .container-inside {
            display: flex;
            background-color: #ffffff;
            box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.3);
            max-width: 900px;
        }
        .btn-log {
            background-color: #D10024;
            transition: background-color 0.3s, box-shadow 0.2s; 
            box-shadow: 0 7px 11px #a0a0a0;
            border: none;
            color: #fff;
            border-radius: 35px;
            width: 55%;
            margin-top: 55px;
            height: 50px;
            font-size: 18px;
            font-weight: 600;
        }
        .btn-log:hover {
            background-color: #B31920; /* Un verde ligeramente más oscuro para el hover */
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2); /* Sombra para dar sensación de elevación */
        }

        /* Estilo para cuando el botón está enfocado o activo */
        .btn-log:focus, .btn-log:active {
            outline: none; /* Elimina el outline que algunos navegadores añaden */
            background-color: #8E171C; /* Un verde aún más oscuro para el focus/active */
        }
        .img-log {
            width: 50%;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .img-log::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('assets/images/login.png');
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
        .form-log {
            width: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            gap: 50px;
            padding: 35px;
        }
        h2 {
            margin-top: 60px;
            font-weight: 400;
            color: #616161;
        }
        .password-container {
            width: 100%;
            display: flex;
            align-items: flex-end;
            margin-top: 25px;
            gap: 20px;
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
        .login-user {
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
        @media (max-width: 768px) {
            .container-inside {
                flex-direction: column;
                box-shadow: none;
                background-color: transparent;
            }
            .img-log {
                display: none;
            }
            .form-log {
                width: 100%;
                padding: 20px;
            }
            .btn-log{
                width: 70%;
            }
        }
    </style>
</head>
<body>
    <?php include 'assets/includes/header.php';?>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p>' . $_SESSION['error'] . '</p>';
    }
    ?>
    <div class="container2">
        <div class="container-inside">
            <div class="img-log">
                <div class="img-int">
                    <img src="assets/images/logo-login.png" alt="Logo">
                </div>
            </div>
            <div class="form-log"> 
                <h2>Bienvenido!</h2>
                <form action="config/processlogin.php" method="post" class="login-user">
                    <div class="password-container">
                        <input type="text" id="username" name="username" placeholder="Usuario" class="password-input" required>
                        <span><i class="fa-solid fa-user" style="font-size:26px"></i></span>
                    </div>
                    <div class="password-container">
                        <input type="password" id="password" name="password" placeholder="Contraseña" class="password-input" required>
                        <span class="show-password" onclick="togglePassword()"><i id="passwordIcon" class="fa-solid fa-lock" style="font-size:26px"></i></span>
                    </div>
                    <input type="submit" value="Iniciar sesión" class="btn-log">
                </form>
                <p><a href="register.php">¿No tenés una cuenta? <span class="register">Registrate acá</span></a></p>
                <p><a href="forgot_password.php">¿Olvidaste tu contraseña?</a></p>
            </div>    
        </div>
    </div>
    <?php include 'assets/includes/footer.php';?>
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
