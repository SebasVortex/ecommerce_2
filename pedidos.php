<?php
include 'config/database.php'; // Incluye tu archivo de configuración con PDO
include 'config/checksession.php'; // Incluye el archivo de verificación de sesión

if (!isset($_SESSION['user_id'])) {
    die('Usuario no autenticado.');
}

$userId = $_SESSION['user_id'];

// Número de pedidos por página
$pedidosPorPagina = 5;

// Obtener el número de página actual
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$paginaActual = max($paginaActual, 1); // Asegurarse de que sea al menos 1

// Calcular el offset para la consulta SQL
$offset = ($paginaActual - 1) * $pedidosPorPagina;

try {
    // Obtener el número total de pedidos para este usuario
    $stmtCount = $conn->prepare('SELECT COUNT(*) AS total FROM pedidos WHERE user_id = :user_id');
    $stmtCount->execute(['user_id' => $userId]);
    $totalPedidos = (int)$stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

    // Calcular el número total de páginas
    $totalPaginas = ceil($totalPedidos / $pedidosPorPagina);

    // Obtener todos los pedidos del usuario con paginación
    $stmt = $conn->prepare('
        SELECT p.id AS pedido_id, p.total, p.status, p.nombre, p.apellido, p.telefono, p.notas, 
               pi.product_id, pi.quantity, pi.price, pr.name AS product_name
        FROM pedidos p
        INNER JOIN pedidos_items pi ON p.id = pi.order_id
        INNER JOIN productos pr ON pi.product_id = pr.id
        WHERE p.user_id = :user_id
        ORDER BY p.id DESC
        LIMIT :limit OFFSET :offset
    ');
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $pedidosPorPagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Agrupar los pedidos por ID
    $pedidosAgrupados = [];
    foreach ($pedidos as $pedido) {
        $pedidoId = $pedido['pedido_id'];
        if (!isset($pedidosAgrupados[$pedidoId])) {
            $pedidosAgrupados[$pedidoId] = [
                'pedido_id' => $pedidoId,
                'total' => $pedido['total'],
                'status' => $pedido['status'],
                'nombre' => $pedido['nombre'],
                'apellido' => $pedido['apellido'],
                'telefono' => $pedido['telefono'],
                'notas' => $pedido['notas'],
                'items' => []
            ];
        }
        $pedidosAgrupados[$pedidoId]['items'][] = [
            'product_id' => $pedido['product_id'],
            'quantity' => $pedido['quantity'],
            'price' => $pedido['price'],
            'product_name' => $pedido['product_name']
        ];
    }
} catch (PDOException $e) {
    $error_message = 'Error al obtener los pedidos: ' . htmlspecialchars($e->getMessage());
}
?>

<?php include 'assets/includes/head.php'; ?>
</head>
<body>
    <!-- HEADER -->
    <?php include 'assets/includes/header.php'; ?>
    <!-- /HEADER -->

    <div class="container mt-4">
        <br>
        <br>
        <h2 class="mb-4">Mis Pedidos</h2>
        <br>
        <br>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php elseif (empty($pedidosAgrupados)): ?>
            <div class="alert alert-info" role="alert">
                No tienes pedidos realizados.
            </div>
        <?php else: ?>
            <?php foreach ($pedidosAgrupados as $pedido): ?>
                <hr>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Pedido ID: <?php echo htmlspecialchars($pedido['pedido_id']); ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($pedido['nombre'] . ' ' . $pedido['apellido']); ?></p>
                                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($pedido['telefono']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Notas:</strong> <?php echo htmlspecialchars($pedido['notas']); ?></p>
                                <p><strong>Status:</strong> <?php echo htmlspecialchars($pedido['status']); ?></p>
                                <p><strong>Total:</strong> $<?php echo number_format($pedido['total'], 2); ?></p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pedido['items'] as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                                            <td>$<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($pedido['status'] == 'pendiente'): ?>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancelModal<?php echo htmlspecialchars($pedido['pedido_id']); ?>">
                                Cancelar Pedido
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <br>
                <br>
                <br>
                <br>
            <?php endforeach; ?>

            <!-- Paginación -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($paginaActual > 1): ?>
                        <li class="page-item"><a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li class="page-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($paginaActual < $totalPaginas): ?>
                        <li class="page-item"><a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <!-- Modal para Confirmar Cancelación -->
    <?php foreach ($pedidosAgrupados as $pedido): ?>
        <?php if ($pedido['status'] == 'pendiente'): ?>
            <div class="modal fade" id="cancelModal<?php echo htmlspecialchars($pedido['pedido_id']); ?>" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cancelModalLabel">Confirmar Cancelación</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ¿ Estás seguro de que deseas cancelar este pedido ?
                        </div>
                        <div class="modal-footer">
                            <form action="config/cancelarpedido.php" method="POST">
                                <input type="hidden" name="pedido_id" value="<?php echo htmlspecialchars($pedido['pedido_id']); ?>">
                                <button type="submit" class="btn btn-danger">Sí, cancelar</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php include 'assets/includes/footer.php'; ?>
</body>
</html>
