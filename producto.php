<?php
include ('config/database.php');

// Consultar productos con el nombre de la marca y de la categoría
$stmt = $conn->prepare("
    SELECT p.*, m.name AS brand_name, c.name AS category_name
    FROM productos p 
    LEFT JOIN marcas m ON p.brand_id = m.id
    LEFT JOIN categorias c ON p.category_id = c.id
");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
