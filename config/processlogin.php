<?php
session_start(); // Asegúrate de que la sesión esté iniciada

include('database.php'); // Incluye el archivo de configuración de la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Limpieza y validación de entradas
    function sanitizeInput($input) {
        return htmlspecialchars(strip_tags($input));
    }

    $username = sanitizeInput($username);
    $password = sanitizeInput($password);

    try {
        // Consulta para obtener el hash de la contraseña y el ID del usuario
        $sql = "SELECT id, password FROM clientes WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $password_hash = $row['password'];
            $user_id = $row['id'];

            // Verificar la contraseña
            if (password_verify($password, $password_hash)) {
                // Contraseña correcta, iniciar sesión
                $_SESSION['user_id'] = $user_id; // Guarda el ID del usuario en la sesión
                $_SESSION['last_activity'] = time(); // Actualiza el tiempo de la última actividad
                header("Location: ../index.php"); // Redirige a la página principal
                exit();
            } else {
                header("Location: ../login.php?error=passwrong");
                exit();
            }
        } else {
            header("Location: ../login.php?error=userwrong");
            exit();
        }

    } catch (PDOException $e) {
        // Manejo de errores en la base de datos
        die("Error en la base de datos: " . $e->getMessage());
    }
}
?>