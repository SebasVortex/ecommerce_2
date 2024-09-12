<!DOCTYPE html>
<html lang="es">
<head>
<link rel="icon" href="../IMAGENES/ICONO_SESA.webp" type="image/x-icon">
    <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap');
        *{
            padding: 0;
            margin: 0;
            font-family: "Quicksand", sans-serif;
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
            width: 280px;
        }
        }
    </style>
</head>
<body>
<div class="wrap-m">
    <div class="wrap-log">
        <div class="logo-sis"><img src="assets/img/person.png"></div>
        <form action="herramientas/loginprocess.php" method="post" class="login-user">
            <div class="password-container">
            <input type="text" id="username" name="username" placeholder="Usuario"  class="password-input"required>
            <span><img src="assets/img/person.png"></span>
            </div>
            <span id="erroruser" style="display: none;">*Usuario incorrecto</span>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Contraseña"class="password-input" required>
                <span class="show-password" onclick="togglePassword()"><img id="passwordIcon" src="assets/img/lock.png" alt="Toggle Password"></span>
            </div>
            <span id="errorpassword" style="display: none;">*Contraseña incorrecta</span>
            <input type="submit" value="LOGIN" class="btn-log">
        </form>
    </div>
</div>

<script>
        function togglePassword() {
            var passwordField = document.getElementById('password');
            var passwordIcon = document.getElementById('passwordIcon');
            var passwordType = passwordField.type;

            if (passwordType === 'password') {
                passwordField.type = 'text';
                passwordIcon.src = 'assets/img/unlock.png'; // Imagen cuando la contraseña está visible
            } else {
                passwordField.type = 'password';
                passwordIcon.src = 'assets/img/lock.png'; // Imagen original
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
    if (url.includes("loginad.php?=userwrong")) {
        errorUserSpan.style.display = "block";
    } else if (url.includes("loginad.php?=passwrong")) {
        errorPasswordSpan.style.display = "block";
    }
});

    </script>
    
</body>
</html>