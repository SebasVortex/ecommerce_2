<?php
include('config/database.php'); // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $productId = $_POST['product_id'];

        try {
            if ($action === 'add') {
                $quantity = $_POST['quantity'];

                // Verificar si el producto ya está en el carrito
                $stmt = $conn->prepare("SELECT * FROM carrito WHERE user_id = :user_id AND product_id = :product_id");
                $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
                $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($carrito) {
                    // Actualizar la cantidad
                    $stmt = $conn->prepare("UPDATE carrito SET quantity = quantity + :quantity WHERE id = :id");
                    $stmt->execute(['quantity' => $quantity, 'id' => $carrito['id']]);
                } else {
                    // Insertar nuevo producto
                    $stmt = $conn->prepare("INSERT INTO carrito (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
                    $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'quantity' => $quantity]);
                }
            } elseif ($action === 'delete') {
                // Eliminar producto del carrito
                $stmt = $conn->prepare("DELETE FROM carrito WHERE user_id = :user_id AND product_id = :product_id");
                $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
            }

            // Obtener los datos actualizados del carrito
            $query = "SELECT p.id, p.name, p.price, p.imagen, c.quantity 
                      FROM carrito c 
                      JOIN productos p ON c.product_id = p.id 
                      WHERE c.user_id = :user_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Calcular total y número de artículos
            $total = 0;
            $total_items = 0;
            foreach ($cart_items as $item) {
                $total += $item['price'] * $item['quantity'];
                $total_items += $item['quantity'];
            }

            // Respuesta JSON con los datos actualizados del carrito
            echo json_encode(['cart_items' => $cart_items, 'total' => $total, 'total_items' => $total_items]);

        } catch (PDOException $e) {
            error_log('Error en la operación del carrito: ' . $e->getMessage());
            echo json_encode(['error' => 'Error al actualizar el carrito']);
        }
    }
}
?>
