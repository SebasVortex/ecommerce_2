<?php

// Verificar el token CSRF
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = 'Token CSRF inválido.';
        header('Location: login.php?error=tokenmalo'); // Corregir el parámetro de la URL
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
        $stmt->bindParam(':email', $username); // Usar el mismo parámetro para username y email
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar la contraseña
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirigir a la página principal u otra página que desees
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error'] = 'Nombre de usuario, email o contraseña incorrectos.';
            header('Location: login.php?error=loginfailed'); // Redirige con error
            exit();
        }
    } catch (PDOException $e) {
        // Manejo de errores de la base de datos
        $_SESSION['error'] = 'Error en la base de datos.';
        header('Location: login.php?error=dberror'); // Redirige con error
        exit();
    }
}

// Asegúrate de que la variable de error se borre al cargar la página
if (isset($_SESSION['error'])) {
    unset($_SESSION['error']);
}
?>

<?php include 'assets/includes/head.php';?>
    <title>Login</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        *{
            padding: 0;
            margin: 0;
        }
         .container2{
          display: flex;
          justify-content: center;
          align-items: center;
          flex-direction: column;
          padding: 5rem 0rem 8rem 0rem;
         }
        .btn-log {
            background-image: linear-gradient(to right, #cc3433 0%, #f74d61  51%, #cc3433  100%);
            text-align: center;
            text-transform: uppercase;
            transition: 0.5s;
            background-size: 200% auto;
            color: white;            
            box-shadow: 0 0 20px #eee;
            border-radius: 10px;
            display: block;
          }

          .btn-log:hover {
            background-position: right center; /* change the direction of the change here */
            color: #fff;
            text-decoration: none;
          }
         
        .password-container {
          display: flex;
          justify-content: space-between;
          align-items: center;
          background-color: #f2f2f2;
          margin-top: 25px;
          border-radius: 15px;
          padding: 0.1rem;
          width: 100%;
        }
        .password-input{
            border: none;
            width: 100%;
            padding: 1rem;
            font-size: 21px;
            background-color: transparent;
        }
        .password-input:focus {
            outline: none; /* Elimina el borde de enfoque */
        }
        .show-password {
            cursor: pointer;
        }
        .login-user{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            flex-direction: column;
            border-radius: 35px;
            max-width: 425px;
            padding: 5.6rem 3rem 5.2rem 3rem;
            background-color: #ffffff;
            box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.3);
        }
        .password-container span img{
            width: 35px;
            margin-right: 15px;
        }
        .wrap-log{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            width: 100%;
            position: relative;
            bottom: 50px;

        }
        .btn-log{
            text-align: center;
            border: none;
            color: #ffffff;
            cursor: pointer;
            border-radius: 15px;
            width: 100%;
            margin-top: 35px;
            height: 60px;
            font-size: 24px;
            font-weight: 600;
        }
        .logo-sis{
            background-color: #cc3433;
            display: grid;
            place-items: center;
            border-radius: 50%;
            padding: 25px;
            position: relative;
            top: 50px;
        }
        .logo-sis img{
            filter: brightness(100);
        }
        .wrap-m{
            display: grid;
            place-items: center;
            width: 100%;
            height: 100vh;
            background-color: #d8d8d8;
        }
        .wrap-m span{
            margin-right: auto;
            color: #d91e22;
            margin-left: 5px;
            margin-top: 10px;
        }
        @media (min-width: 320px) and (max-width: 768px) {
        .login-user{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            flex-direction: column;
            border-radius: 35px;
            max-width: 425px;
            padding: 5.6rem 3rem 5.2rem 3rem;
            background-color: #ffffff;
            box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.0);
        }
        }
        .login-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            margin-bottom: 20px;
        }
        .login-header h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .login-footer {
            margin-top: 20px;
        }
        .login-footer p {
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
    <div class="container2">
            <div class="login-header text-center">
                <h2>Iniciar Sesión</h2>
            </div>
            <form action="config/processlogin.php" method="post" class="login-user">
                <div class="password-container">
                    <input type="text" id="username" name="username" placeholder="Usuario"  class="password-input"required>
                    <span><img src="assets/images/person.png"></span>
                </div>
                <span id="erroruser" style="display: none;">*Usuario incorrecto</span>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Contraseña"class="password-input" required>
                    <span class="show-password" onclick="togglePassword()"><img id="passwordIcon" src="assets/images/lock.png" alt="Toggle Password"></span>
                </div>
                <span id="errorpassword" style="display: none;">*Contraseña incorrecta</span>
                    <input type="submit" value="Iniciar sesion" class="btn-log">
        </form>
            <div class="login-footer text-center">
                <p><a href="register.php">Crear una cuenta</a></p>
            </div>
    </div>
<script>
     function togglePassword() {
            var passwordField = document.getElementById('password');
            var passwordIcon = document.getElementById('passwordIcon');
            var passwordType = passwordField.type;

            if (passwordType === 'password') {
                passwordField.type = 'text';
                passwordIcon.src = 'assets/images/unlock.png'; // Imagen cuando la contraseña está visible
            } else {
                passwordField.type = 'password';
                passwordIcon.src = 'assets/images/lock.png'; // Imagen original
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
    // Obtener la URL actual
    var url = window.location.href;

    // Obtener los elementos span por su ID
    var errorUserSpan = document.getElementById("erroruser");
    var errorPasswordSpan = document.getElementById("errorpassword");

    // Función para ocultar ambos spans
    function hideSpans() {
        errorUserSpan.style.display = "none";
        errorPasswordSpan.style.display = "none";
    }

    // Ocultar ambos spans por defecto
    hideSpans();

    // Verificar la URL y mostrar el span correspondiente
    if (url.includes("login.php?=userwrong")) {
        errorUserSpan.style.display = "block";
    } else if (url.includes("login.php?=passwrong")) {
        errorPasswordSpan.style.display = "block";
    }
});

</script>
    		<!-- PIE DE PÁGINA -->
		<?php include 'assets/includes/footer.php';?>
		<!-- /PIE DE PÁGINA -->
</body>
</html>