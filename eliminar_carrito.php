<?php
include('config/database.php');
include('config/checksession.php');

// Verifica si se envió un ID de producto para eliminar del carrito
if (isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    // Lógica para eliminar el producto del carrito
    $query = "DELETE FROM carrito WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $deleteId, PDO::PARAM_INT);
    $stmt->execute();

    // Redirigir a la misma página para actualizar el carrito
    header('Location: carrito.php');
    exit;
}

// Código para manejar otras solicitudes y mostrar el contenido del carrito
// ...

?>