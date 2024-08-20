<?php
include '../config/database.php'; // Incluye tu archivo de configuración con PDO
include '../config/checksession.php';

// Verificar que el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    die('Acceso denegado.');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de pedido inválido.');
}

$orderId = intval($_GET['id']);

try {
    // Consultar los detalles del pedido
    $stmt = $conn->prepare('
        SELECT p.*, u.username AS user_username, u.email AS user_email
        FROM pedidos p
        JOIN clientes u ON p.user_id = u.id
        WHERE p.id = :order_id
    ');
    $stmt->execute(['order_id' => $orderId]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        die('Pedido no encontrado.');
    }

    // Consultar los items del pedido
    $stmt = $conn->prepare('
        SELECT pi.*, pr.name, pr.price
        FROM pedidos_items pi
        JOIN productos pr ON pi.product_id = pr.id
        WHERE pi.order_id = :order_id
    ');
    $stmt->execute(['order_id' => $orderId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die('Error al consultar el pedido: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Pedido</title>
    <link rel="stylesheet" href="styles.css"> <!-- Incluye tus estilos -->
</head>
<body>
    <h1>Detalles del Pedido #<?php echo htmlspecialchars($pedido['id']); ?></h1>
    <p><strong>Total:</strong> <?php echo number_format($pedido['total'], 2); ?> USD</p>
    <p><strong>Estado:</strong> <?php echo htmlspecialchars($pedido['status']); ?></p>
    <p><strong>Nombre del Cliente:</strong> <?php echo htmlspecialchars($pedido['nombre']) . ' ' . htmlspecialchars($pedido['apellido']); ?></p>
    <p><strong>Email del Cliente:</strong> <?php echo htmlspecialchars($pedido['user_email']); ?></p>
    <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($pedido['telefono']); ?></p>
    <p><strong>Notas:</strong> <?php echo htmlspecialchars($pedido['notas']); ?></p>
    <h2>Items del Pedido</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td><?php echo number_format($item['price'], 2); ?> USD</td>
                <td><?php echo number_format($item['quantity'] * $item['price'], 2); ?> USD</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="admin_pedidos.php">Volver a la lista de pedidos</a>
</body>
</html>
