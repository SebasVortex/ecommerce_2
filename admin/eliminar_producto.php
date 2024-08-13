<?php
// Conectar a la base de datos
include('../config/database.php');

// Verificar si se recibió un ID válido
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Preparar la consulta de eliminación
    $stmt = $conn->prepare("DELETE FROM productos WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Producto eliminado correctamente.";
    } else {
        echo "Error al eliminar el producto.";
    }
} else {
    echo "ID de producto no válido.";
}

// Redirigir de vuelta a la lista de productos después de eliminar
header("Location: tabla_productos.php");
exit();
?>
