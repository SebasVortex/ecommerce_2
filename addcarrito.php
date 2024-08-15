<?php
include('config/database.php'); // Aquí se incluye la conexión a la base de datos y se inicia la sesión

session_start(); // Iniciar sesión

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    // Si no está logueado, redirigir al login
    header('Location: login.php');
    exit;
}

// Verificar si product_id está definido en la solicitud
if (!isset($_POST['product_id'])) {
    die('Error: No se envió el ID del producto.');
}

$product_id = $_POST['product_id'];
$user_id = $_SESSION['user_id'];

// Comprobar si el producto ya está en el carrito del usuario
$query = "SELECT id FROM carrito WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $conn->errorInfo()[2]);
}

$stmt->execute([$user_id, $product_id]);
if ($stmt->rowCount() > 0) {
    // Si el producto ya está en el carrito, puedes actualizar la cantidad
    $query = "UPDATE carrito SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conn->errorInfo()[2]);
    }
    $stmt->execute([$user_id, $product_id]);
} else {
    // Si el producto no está en el carrito, agregarlo
    $query = "INSERT INTO carrito (user_id, product_id, quantity) VALUES (?, ?, 1)";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conn->errorInfo()[2]);
    }
    $stmt->execute([$user_id, $product_id]);
}

// Redirigir al carrito o mostrar un mensaje de éxito
if ($stmt) {

} else {
    // Manejar el error
    echo "Error al agregar el producto al carrito: " . $stmt->errorInfo()[2];
}

$conn = null;
?>
