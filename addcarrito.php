<?php
// Incluir la configuración de la base de datos y la verificación de sesión
include('config/database.php');
include('config/checksession.php'); // Incluir el archivo de verificación de sesión

// Función para agregar producto al carrito
function agregarProductoAlCarrito($userId, $productId, $quantity) {
    global $conn; // Usa $conn en lugar de $pdo

    try {
        // Verifica si el producto ya está en el carrito para este usuario
        $stmt = $conn->prepare("SELECT * FROM carrito WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($carrito) {
            // Si el producto ya está en el carrito, actualiza la cantidad
            $stmt = $conn->prepare("UPDATE carrito SET quantity = quantity + :quantity WHERE id = :id");
            $stmt->execute(['quantity' => $quantity, 'id' => $carrito['id']]);
        } else {
            // Si el producto no está en el carrito, insértalo
            $stmt = $conn->prepare("INSERT INTO carrito (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
            $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'quantity' => $quantity]);
        }
    } catch (PDOException $e) {
        // Manejo de errores
        error_log('Error en la consulta: ' . $e->getMessage());
        echo "Error al agregar el producto al carrito.";
    }
}

// Verificar si los datos POST están presentes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID del usuario desde la sesión
    $userId = $_SESSION['user_id']; // Asumiendo que el ID del usuario se almacena en la sesión

    // Obtener los datos del producto y la cantidad desde el POST
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Llamar a la función para agregar el producto al carrito
    agregarProductoAlCarrito($userId, $productId, $quantity);

    echo "Producto agregado al carrito con éxito!";
} else {
    echo "Solicitud inválida.";
}
?>
