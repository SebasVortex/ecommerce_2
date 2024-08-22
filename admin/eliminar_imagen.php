<?php
// Conectar a la base de datos
include('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['image_id']) && !empty($_POST['image_id'])) {
        $imageId = intval($_POST['image_id']);

        // Obtener el nombre del archivo para eliminar del servidor
        $stmt = $conn->prepare("SELECT imagen FROM productos_imagenes WHERE id = :id");
        $stmt->bindParam(':id', $imageId, PDO::PARAM_INT);
        $stmt->execute();
        $image = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($image) {
            $imagePath = '../assets/images/' . $image['imagen'];

            // Eliminar la imagen de la base de datos
            $stmt = $conn->prepare("DELETE FROM productos_imagenes WHERE id = :id");
            $stmt->bindParam(':id', $imageId, PDO::PARAM_INT);
            $stmt->execute();

            // Redirigir o mostrar un mensaje de éxito
            header('Location: tabla_productos.php');
            exit;
        }
    }
}
?>
