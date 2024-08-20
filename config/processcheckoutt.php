<?php
include 'database.php'; // Incluye tu archivo de configuración con PDO

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $zipCode = $_POST['zip-code'];
    $tel = $_POST['tel'];
    $notas = $_POST['notas'];

    // Verificar que todos los campos estén completos
    if (empty($firstName) || empty($lastName) || empty($email) || empty($address) || empty($city) || empty($country) || empty($zipCode) || empty($tel)) {
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

        // Insertar el pedido
        $stmt = $conn->prepare('INSERT INTO pedidos (user_id, total, status, nombre, apellido, telefono, notas) VALUES (:user_id, :total, :status, :nombre, :apellido, :telefono, :notas)');
        $total = 0; // Asegúrate de calcular el total correctamente
        $status = 'pending';
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

        // Verificar si hay artículos en el carrito
        if (isset($_SESSION['cart_items']) && is_array($_SESSION['cart_items'])) {
            // Insertar los items del pedido
            foreach ($_SESSION['cart_items'] as $item) {
                $stmt = $conn->prepare('INSERT INTO pedidos_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)');
                $stmt->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
        } else {
            // Manejar el caso en que el carrito esté vacío o no esté definido
            throw new Exception('El carrito está vacío o no está definido.');
        }

        // Insertar en el historial de pedidos
        $stmt = $conn->prepare('INSERT INTO pedidos_historial (order_id, status) VALUES (:order_id, :status)');
        $stmt->execute(['order_id' => $orderId, 'status' => $status]);

        // Confirmar la transacción
        $conn->commit();

        // Limpiar el carrito
        unset($_SESSION['cart_items']);

        echo 'Pedido realizado con éxito.';

    } catch (Exception $e) {
        // Deshacer la transacción en caso de error
        $conn->rollBack();
        echo 'Error al procesar el pedido: ' . $e->getMessage();
    }
} else {
    echo 'Solicitud inválida.';
}
?>
