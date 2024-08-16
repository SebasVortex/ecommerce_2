<?php
include ('database.php');

// Consultar productos con el nombre de la marca y de la categoría, en orden aleatorio
$stmt = $conn->prepare("
    SELECT p.*, m.name AS brand_name, c.name AS category_name
    FROM productos p 
    LEFT JOIN marcas m ON p.brand_id = m.id
    LEFT JOIN categorias c ON p.category_id = c.id
    ORDER BY RAND()
    LIMIT 5
");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consultar hasta 3 productos de la categoría "inversores"
$stmt_inversores = $conn->prepare("
    SELECT p.*, m.name AS brand_name, c.name AS category_name
    FROM productos p 
    LEFT JOIN marcas m ON p.brand_id = m.id
    LEFT JOIN categorias c ON p.category_id = c.id
    WHERE c.name = 'inversores'
    ORDER BY RAND()
    LIMIT 3
");
$stmt_inversores->execute();
$productos_inversores = $stmt_inversores->fetchAll(PDO::FETCH_ASSOC);

// Consultar hasta 3 productos de la categoría "sistemas hibridos"
$stmt_sistemas_hibridos = $conn->prepare("
    SELECT p.*, m.name AS brand_name, c.name AS category_name
    FROM productos p 
    LEFT JOIN marcas m ON p.brand_id = m.id
    LEFT JOIN categorias c ON p.category_id = c.id
    WHERE c.name = 'sistemas hibridos'
    ORDER BY RAND()
    LIMIT 3
");
$stmt_sistemas_hibridos->execute();
$productos_sistemas_hibridos = $stmt_sistemas_hibridos->fetchAll(PDO::FETCH_ASSOC);

// Consultar hasta 3 productos de la categoría "baterias"
$stmt_baterias = $conn->prepare("
    SELECT p.*, m.name AS brand_name, c.name AS category_name
    FROM productos p 
    LEFT JOIN marcas m ON p.brand_id = m.id
    LEFT JOIN categorias c ON p.category_id = c.id
    WHERE c.name = 'baterias'
    ORDER BY RAND()
    LIMIT 3
");
$stmt_baterias->execute();
$productos_baterias = $stmt_baterias->fetchAll(PDO::FETCH_ASSOC);


// Ejecutar la consulta para obtener productos, marcas y categorías en orden aleatorio
$stmt_store = $conn->prepare("
    SELECT p.*, m.name AS brand_name, c.name AS category_name
    FROM productos p 
    LEFT JOIN marcas m ON p.brand_id = m.id
    LEFT JOIN categorias c ON p.category_id = c.id
    ORDER BY RAND()
");
$stmt_store->execute();
$productostore = $stmt_store->fetchAll(PDO::FETCH_ASSOC)



?>
