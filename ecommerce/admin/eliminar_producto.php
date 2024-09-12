<?php
// Conectar a la base de datos
include('../config/database.php');

// Verificar si se recibió un ID válido
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        // Iniciar la transacción
        $conn->beginTransaction();

        // Eliminar los registros en la tabla `carrito` que referencian el producto
        $stmt = $conn->prepare("DELETE FROM carrito WHERE product_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Ahora eliminar el producto de la tabla `productos`
        $stmt = $conn->prepare("DELETE FROM productos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Confirmar la transacción
            $conn->commit();
            echo "Producto eliminado correctamente.";
        } else {
            // Si hay un error, revertir la transacción
            $conn->rollBack();
            echo "Error al eliminar el producto.";
        }
    } catch (Exception $e) {
        // En caso de cualquier excepción, revertir la transacción
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ID de producto no válido.";
}

// Redirigir de vuelta a la lista de productos después de eliminar
header("Location: tabla_productos.php");
exit();
?>
