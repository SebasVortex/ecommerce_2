<?php
include 'database.php'; // Incluye tu archivo de configuración con PDO
include 'checksession.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    // Remueve la línea que recoge el país
    // $country = $_POST['country'];
    $zipCode = $_POST['zip-code'];
    $tel = $_POST['tel'];
    $notas = $_POST['notas'];

    // Verificar que todos los campos estén completos
    if (empty($firstName) || empty($lastName) || empty($email) || empty($address) || empty($city) || empty($zipCode) || empty($tel)) {
        die('Por favor, complete todos los campos obligatorios.');
    }

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION['user_id'])) {
        die('Usuario no autenticado.');
    }

    $userId = $_SESSION['user_id'];

    try {
        // Verificar si el usuario existe
        $stmt = $conn->prepare('SELECT COUNT(*) FROM clientes WHERE id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $userExists = $stmt->fetchColumn();

        if ($userExists == 0) {
            die('El usuario no existe.');
        }

        // Iniciar una transacción
        $conn->beginTransaction();

        // Calcular el total del pedido
        $total = 0;
        if (isset($_SESSION['cart_items']) && is_array($_SESSION['cart_items'])) {
            foreach ($_SESSION['cart_items'] as $item) {
                // Consultar el precio del producto en la base de datos
                $stmt = $conn->prepare('SELECT price FROM productos WHERE id = :product_id');
                $stmt->execute(['product_id' => $item['product_id']]);
                $price = $stmt->fetchColumn();
                
                if ($price === false) {
                    throw new Exception('No se encontró el precio para el producto con ID: ' . $item['product_id']);
                }
                
                // Calcular el subtotal para este artículo
                $total += $price * $item['quantity'];
            }
        } else {
            throw new Exception('El carrito está vacío o no está definido.');
        }

        // Insertar el pedido
        $stmt = $conn->prepare('INSERT INTO pedidos (user_id, total, status, nombre, apellido, telefono, notas) VALUES (:user_id, :total, :status, :nombre, :apellido, :telefono, :notas)');
        $status = 'pendiente';
        $stmt->execute([
            'user_id' => $userId,
            'total' => $total,
            'status' => $status,
            'nombre' => $firstName,
            'apellido' => $lastName,
            'telefono' => $tel,
            'notas' => $notas
        ]);

        // Obtener el ID del pedido recién creado
        $orderId = $conn->lastInsertId();

        // Insertar los items del pedido
        foreach ($_SESSION['cart_items'] as $item) {
            // Consultar el precio del producto en la base de datos
            $stmt = $conn->prepare('SELECT price FROM productos WHERE id = :product_id');
            $stmt->execute(['product_id' => $item['product_id']]);
            $price = $stmt->fetchColumn();
            
            if ($price === false) {
                throw new Exception('No se encontró el precio para el producto con ID: ' . $item['product_id']);
            }
            
            // Insertar en pedidos_items
            $stmt = $conn->prepare('INSERT INTO pedidos_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)');
            $stmt->execute([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $price
            ]);
        }

        // Insertar en el historial de pedidos
        $stmt = $conn->prepare('INSERT INTO pedidos_historial (order_id, status) VALUES (:order_id, :status)');
        $stmt->execute(['order_id' => $orderId, 'status' => $status]);

        // Eliminar los elementos del carrito del usuario
        $stmt = $conn->prepare('DELETE FROM carrito WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);

        // Confirmar la transacción
        $conn->commit();

        // Limpiar el carrito de la sesión
        unset($_SESSION['cart_items']);

        header("Location: ../index.php?pedido=realizado");
        exit();

    } catch (Exception $e) {
        // Deshacer la transacción en caso de error
        $conn->rollBack();
        echo 'Error al procesar el pedido: ' . $e->getMessage();
    }
} else {
    echo 'Solicitud inválida.';
    header("Location: ../index.php?pedido=invalido");
    exit();
}
?>
