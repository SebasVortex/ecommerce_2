<?php
include ('database.php');

session_start(); // Iniciar sesión

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Actualizar el estado de solicitud de verificación en la base de datos
    $stmt = $conn->prepare("UPDATE clientes SET solicitud_verificacion = 'si' WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: ../sheet.php?solicitud=enviada"); // Redirigir con un mensaje de confirmación
    exit();
} catch (PDOException $e) {
    echo "Error al solicitar verificación: " . htmlspecialchars($e->getMessage());
}
?>
