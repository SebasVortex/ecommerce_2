<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
include 'database.php';

// Obtener el término de búsqueda desde la solicitud AJAX
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Construir la consulta SQL
$query = "SELECT p.id, p.name, p.price, m.name AS brand_name, c.name AS category_name
          FROM productos p
          LEFT JOIN marcas m ON p.brand_id = m.id
          LEFT JOIN categorias c ON p.category_id = c.id
          WHERE p.name LIKE ? OR m.name LIKE ? OR c.name LIKE ?
          ORDER BY p.name ASC";

$params = array_fill(0, 3, '%' . $searchTerm . '%');

try {
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los resultados en formato JSON
    echo json_encode($results);
} catch (PDOException $e) {
    // Manejo de errores
    echo json_encode([]);
    error_log('Error en la consulta de búsqueda: ' . $e->getMessage());
}
?>
