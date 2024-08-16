<?php
include('database.php');

// Verifica si la categoría está presente en la solicitud
if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);

    // Consulta para obtener los productos de la categoría seleccionada
    $sql = "SELECT * FROM productos WHERE category_id = :category_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmt->execute();

    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devuelve los productos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($productos);
} else {
    echo json_encode(["error" => "Categoría no especificada."]);
}
?>
