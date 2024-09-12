<?php
include 'database.php'; // Incluye tu archivo de configuración con PDO
include 'checksession.php'; // Incluye el archivo de verificación de sesión

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        $current_password = trim($_POST['current_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Fetch the current password from the database
        $stmt = $conn->prepare("SELECT password FROM clientes WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $stmt = $conn->prepare("UPDATE clientes SET password = :new_password WHERE id = :user_id");
                $stmt->bindParam(':new_password', $hashed_password);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                header("Location: ../editar_perfil.php?verificado=contrasena_cambiada");
                exit();
            } else {
                // Redirige si las contraseñas no coinciden
                header("Location: ../editar_perfil.php?error=error_contrasena");
                exit();
            }
        } else {
            // Redirige si la contraseña actual es incorrecta
            header("Location: ../editar_perfil.php?error=contrasena_incorrecta");
            exit();
        }
    } else {
        echo "Por favor completa todos los campos.";
    }
}