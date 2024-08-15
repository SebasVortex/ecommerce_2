<?php
session_start();

// Conectar a la base de datos
include('config/database.php');

// Verificar si se ha pasado un ID de producto
if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);

    // Verificar si el carrito ya existe en la sesión
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Verificar si el producto ya está en el carrito
    if (isset($_SESSION['carrito'][$productId])) {
        // Si ya está, incrementar la cantidad
        $_SESSION['carrito'][$productId]['quantity'] += 1;
    } else {
        // Obtener los detalles del producto desde la base de datos
        $stmt = $conn->prepare("SELECT * FROM productos WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Agregar el producto al carrito si se encontró en la base de datos
        if ($product) {
            $_SESSION['carrito'][$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
                'imagen' => $product['imagen']
            ];
        }
    }

    // Redirigir al usuario a la página anterior
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    // Redirigir a una página de error si no se pasó una ID válida
    header('Location: error.php');
    exit();
}
?>
