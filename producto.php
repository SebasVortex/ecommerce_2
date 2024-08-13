<?php
include ('config/database.php');

// Consultar productos con el nombre de la marca
$stmt = $conn->prepare("
    SELECT p.*, m.name AS brand_name 
    FROM productos p 
    LEFT JOIN marcas m ON p.brand_id = m.id
");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
