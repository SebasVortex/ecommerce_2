<?php
include 'database.php'; // Incluye tu archivo de configuración con PDO
include 'checksession.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger el tipo de usuario
    $userType = $_POST['userType'];

    // Inicializar variables
    $firstName = null;
    $lastName = null;
    $razonSocial = null;
    $cuit = null;
    $personaContacto = null;
    $email = null;
    $tel = null;
    $email = $_POST['consumidor-email'] ?? $_POST['empresa-email'];

    if ($userType == 'consumidor') {
        // Recoger los datos del formulario para Consumidor Final
        $firstName = $_POST['consumidor-first-name'];
        $lastName = $_POST['consumidor-last-name'];
        $email = $_POST['consumidor-email'];
        $tel = $_POST['consumidor-tel'];

        // Verificar que todos los campos estén completos
        if (empty($firstName) || empty($lastName) || empty($email) || empty($tel)) {
            die('Por favor, complete todos los campos obligatorios.');
        }
    } elseif ($userType == 'empresa') {
        // Recoger los datos del formulario para Empresa
        $razonSocial = $_POST['empresa-razon-social'];
        $cuit = $_POST['empresa-cuit'];
        $email = $_POST['empresa-email'];
        $tel = $_POST['empresa-tel'];
        $personaContacto = $_POST['empresa-persona-contacto'];

        // Verificar que todos los campos estén completos
        if (empty($razonSocial) || empty($cuit) || empty($email) || empty($tel) || empty($personaContacto)) {
            die('Por favor, complete todos los campos obligatorios.');
        }
    } else {
        die('Tipo de usuario no válido.');
    }

    $notas = $_POST['notas'];

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

        // Calcular el total del pedido
        $total = 0;
        $cartItems = $_SESSION['cart_items'] ?? []; // Asegúrate de que `$_SESSION['cart_items']` esté definido

        if (empty($cartItems)) {
            header("Location: ../checkout.php?pedido=vacio");
            exit();
            die('El carrito está vacío. No se puede procesar el pedido.');

        }

        foreach ($cartItems as $item) {
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

        // Iniciar una transacción
        $conn->beginTransaction();

        // Insertar el pedido dependiendo del tipo de usuario
        $stmt = $conn->prepare('
            INSERT INTO pedidos (user_id, total, status, nombre, apellido, razon_social, cuit, telefono, notas, persona_contacto, email) 
            VALUES (:user_id, :total, :status, :nombre, :apellido, :razon_social, :cuit, :telefono, :notas, :persona_contacto, :email)
        ');

        $stmt->execute([
            'user_id' => $userId,
            'total' => $total,
            'status' => 'pendiente',
            'nombre' => $userType == 'consumidor' ? $firstName : null,
            'apellido' => $userType == 'consumidor' ? $lastName : null,
            'razon_social' => $userType == 'empresa' ? $razonSocial : null,
            'cuit' => $userType == 'empresa' ? $cuit : null,
            'telefono' => $tel,
            'notas' => $notas,
            'persona_contacto' => $userType == 'empresa' ? $personaContacto : null,
            'email' => $email // Agrega este parámetro
        ]);

        // Obtener el ID del pedido recién creado
        $orderId = $conn->lastInsertId();

        // Insertar los items del pedido
        foreach ($cartItems as $item) {
            $stmt = $conn->prepare('
                INSERT INTO pedidos_items (order_id, product_id, quantity, price) 
                VALUES (:order_id, :product_id, :quantity, :price)
            ');
            $stmt->execute([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $price
            ]);
        }

        // Insertar en el historial de pedidos
        $stmt = $conn->prepare('INSERT INTO pedidos_historial (order_id, status) VALUES (:order_id, :status)');
        $stmt->execute(['order_id' => $orderId, 'status' => 'pendiente']);

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
