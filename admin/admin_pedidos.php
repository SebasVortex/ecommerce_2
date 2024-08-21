<?php
include '../config/database.php'; // Incluye tu archivo de configuración con PDO
include '../config/checksession.php';



try {
    // Consultar todos los pedidos
    $stmt = $conn->prepare('
        SELECT p.id, p.total, p.status, p.nombre, p.apellido, p.telefono, COUNT(pi.id) AS items_count
        FROM pedidos p
        LEFT JOIN pedidos_items pi ON p.id = pi.order_id
        GROUP BY p.id
        ORDER BY p.id DESC
    ');
    $stmt->execute();
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die('Error al consultar los pedidos: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Pedidos</title>
    <link rel="stylesheet" href="styles.css"> <!-- Incluye tus estilos -->
</head>
<body>
    <h1>Pedidos</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Items</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                <td><?php echo number_format($pedido['total'], 2); ?> USD</td>
                <td><?php echo htmlspecialchars($pedido['status']); ?></td>
                <td><?php echo htmlspecialchars($pedido['nombre']); ?></td>
                <td><?php echo htmlspecialchars($pedido['apellido']); ?></td>
                <td><?php echo htmlspecialchars($pedido['telefono']); ?></td>
                <td><?php echo htmlspecialchars($pedido['items_count']); ?></td>
                <td>
                    <a href="ver_pedido.php?id=<?php echo $pedido['id']; ?>">Ver más</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
