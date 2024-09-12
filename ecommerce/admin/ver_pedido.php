<?php
// Conectar a la base de datos
include('../gestionadmin/herramientas/check.php');
include('../gestionadmin/herramientas/dbconex.php');
date_default_timezone_set('America/Argentina/Buenos_Aires'); // Ajusta esto a tu zona horaria
// Verificar el rol del usuario
$username = $_SESSION['username'];
$sql = "SELECT role FROM usuarios WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $role = $row['role'];
} else {
    echo "Usuario no encontrado.";
    exit();
}

if ($role != 'admine') {
    echo "No tienes permiso para acceder a esta página.";
    exit();
}

?>
<?php
include '../config/database.php'; // Incluye tu archivo de configuración con PDO
include '../config/checksession.php';

// Validar el ID del pedido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de pedido inválido.');
}

$orderId = intval($_GET['id']);

try {
    // Consultar los detalles del pedido
    $stmt = $conn->prepare('
        SELECT p.*, u.username AS user_username, u.email AS user_email, p.user_type AS user_type
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Incluye Bootstrap -->
</head>
<body class="bg-light">
<?php include('assets/menu.php'); ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Detalles del Pedido #<?php echo htmlspecialchars($pedido['id']); ?></h1>

        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Total:</strong> <?php echo number_format($pedido['total'], 2); ?> USD</p>
                <p><strong>Estado:</strong> <?php echo htmlspecialchars($pedido['status']); ?></p>
                <p><strong>Nombre del Cliente:</strong> <?php echo htmlspecialchars($pedido['nombre']) . ' ' . htmlspecialchars($pedido['apellido']); ?> <?php echo htmlspecialchars($pedido['persona_contacto']); ?></p>
                <p><strong>Email del Cliente:</strong> <?php echo htmlspecialchars($pedido['user_email']); ?> | <?php echo htmlspecialchars($pedido['email']); ?></p>
                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($pedido['telefono']); ?></p>
                <p><strong>Tipo de Usuario:</strong> <?php echo htmlspecialchars($pedido['user_type']); ?></p> <!-- Mostrar tipo de usuario -->
                <p><strong>Notas:</strong> <?php echo htmlspecialchars($pedido['notas']); ?></p>
            </div>
        </div>

        <h2 class="text-center mb-4">Items del Pedido</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
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
        </div>

        <div class="text-center mt-4">
            <a href="admin_pedidos.php" class="btn btn-secondary">Volver a la lista de pedidos</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
