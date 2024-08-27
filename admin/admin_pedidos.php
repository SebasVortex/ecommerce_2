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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Incluye Bootstrap -->
</head>
<body class="bg-light">
<?php include('assets/menu.php') ; ?>
    <div class="container mt-5">
        <h1 class="text-left mb-4">Pedidos</h1>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
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
                            <a href="ver_pedido.php?id=<?php echo $pedido['id']; ?>" class="btn btn-primary btn-sm">Ver más</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
