<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
include 'database.php';

// Obtener y sanitizar el término de búsqueda
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Limitar la longitud del término de búsqueda para prevenir abusos
$searchTerm = substr($searchTerm, 0, 50);

// Verificar que el término no esté vacío después de sanitizar
if ($searchTerm === '') {
    echo json_encode([]);
    exit;
}

// Construir la consulta SQL con un límite de resultados
$query = "SELECT p.id, p.name, p.price, p.imagen, m.name AS brand_name, c.name AS category_name
          FROM productos p
          LEFT JOIN marcas m ON p.brand_id = m.id
          LEFT JOIN categorias c ON p.category_id = c.id
          WHERE p.name LIKE ? OR m.name LIKE ? OR c.name LIKE ?
          ORDER BY p.name ASC
          LIMIT 10"; // Limita a 10 resultados

// Preparar los parámetros de búsqueda
$params = array_fill(0, 3, '%' . $searchTerm . '%');

try {
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Comprobar si hay resultados
    if (count($results) > 0) {
        // Devolver los resultados en formato JSON
        echo json_encode($results);
    } else {
        // Si no hay resultados, devolver un array vacío
        echo json_encode([]);
    }
} catch (PDOException $e) {
    // Manejo de errores: registrar pero no exponer detalles al usuario
    error_log('Error en la consulta de búsqueda: ' . $e->getMessage());
    http_response_code(500); // Responder con un error 500
    echo json_encode(['error' => 'Error interno del servidor']);
}
?>