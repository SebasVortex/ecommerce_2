<?php
session_start();

// Establecer el tiempo de expiración de la sesión (en segundos)
// Por ejemplo, 30 minutos = 1800 segundos
$session_lifetime = 1800; // Cambia esto a 1800 si prefieres 30 minutos

ini_set('session.gc_maxlifetime', $session_lifetime);
session_set_cookie_params($session_lifetime);

include 'dbconex.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Escapar datos para evitar inyecciones SQL
    $username = $conn->real_escape_string($username);

    // Consulta para obtener el hash de la contraseña del usuario
    $sql = "SELECT password_hash FROM usuarios WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $password_hash = $row['password_hash'];

        // Verificar la contraseña
        if (password_verify($password, $password_hash)) {
            // Contraseña correcta, iniciar sesión
            $_SESSION['username'] = $username;
            $_SESSION['last_activity'] = time(); // Actualiza el tiempo de la última actividad
            header("Location: ../iniciodata.php");
            exit();
        } else {
            header("Location: ../loginad.php?=passwrong");
        }
    } else {
        header("Location: ../loginad.php?=userwrong");
    }

    $stmt->close();
    $conn->close();
}
?>
