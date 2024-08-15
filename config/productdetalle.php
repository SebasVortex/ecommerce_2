<?php
// Conectar a la base de datos
include('config/database.php');

// Recuperar los datos del producto junto con la marca y la categoría
$product = null;
$imagenes = []; // Variable para almacenar las imágenes adicionales
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("
        SELECT p.*, m.name AS brand_name, c.name AS category_name
        FROM productos p
        LEFT JOIN marcas m ON p.brand_id = m.id
        LEFT JOIN categorias c ON p.category_id = c.id
        WHERE p.id = :id
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Obtener las imágenes adicionales del producto
    $stmt = $conn->prepare("
        SELECT imagen 
        FROM productos_imagenes
        WHERE producto_id = :id
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Decodificar características de JSON
$characteristics = [];
if (!empty($product['characteristics'])) {
    $characteristics = json_decode($product['characteristics'], true);
}

// Obtener productos adicionales
$relatedProducts = [];
if ($product) {
    $stmt = $conn->prepare("
        SELECT p.*, m.name AS brand_name, c.name AS category_name
        FROM productos p
        LEFT JOIN marcas m ON p.brand_id = m.id
        LEFT JOIN categorias c ON p.category_id = c.id
        WHERE p.id != :id
        LIMIT 4
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $relatedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>