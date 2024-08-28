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

// Parámetros de paginación
$limit = 10; // Número de pedidos por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Parámetros de filtro
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$userTypeFilter = isset($_GET['user_type']) ? $_GET['user_type'] : ''; // Definir la variable $userTypeFilter

// Construir consulta SQL con filtro y paginación
$sql = '
    SELECT p.id, p.total, p.status, p.nombre, p.apellido, p.telefono, p.email, p.razon_social, p.cuit, p.notas, p.persona_contacto, p.user_type, COUNT(pi.id) AS items_count
    FROM pedidos p
    LEFT JOIN pedidos_items pi ON p.id = pi.order_id
    WHERE (:statusFilter = "" OR p.status = :statusFilter)
    AND (:userTypeFilter = "" OR p.user_type = :userTypeFilter)
    GROUP BY p.id
    ORDER BY p.id DESC
    LIMIT :limit OFFSET :offset
';

try {
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':statusFilter', $statusFilter, PDO::PARAM_STR);
    $stmt->bindValue(':userTypeFilter', $userTypeFilter, PDO::PARAM_STR); // Vincular la variable $userTypeFilter
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener el número total de pedidos para la paginación
    $countStmt = $conn->prepare('
        SELECT COUNT(DISTINCT p.id) AS total_count
        FROM pedidos p
        LEFT JOIN pedidos_items pi ON p.id = pi.order_id
        WHERE (:statusFilter = "" OR p.status = :statusFilter)
        AND (:userTypeFilter = "" OR p.user_type = :userTypeFilter)
    ');
    $countStmt->bindValue(':statusFilter', $statusFilter, PDO::PARAM_STR);
    $countStmt->bindValue(':userTypeFilter', $userTypeFilter, PDO::PARAM_STR); // Vincular la variable $userTypeFilter
    $countStmt->execute();
    $totalCount = $countStmt->fetchColumn();

    $totalPages = ceil($totalCount / $limit);
    $currentPage = $page;
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
<?php include('assets/menu.php'); ?>
    <div class="container mt-5" style="max-width: 1440px;">
        <h1 class="text-left mb-4">Pedidos</h1>

       <!-- Formulario de filtrado -->
            <form method="GET" class="mb-4">
            <div class="form-row align-items-end">
            <div class="col-auto">
                    <label for="status">Estado:</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Todos</option>
                        <option value="pendiente" <?php echo isset($_GET['status']) && $_GET['status'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="procesado" <?php echo isset($_GET['status']) && $_GET['status'] == 'procesado' ? 'selected' : ''; ?>>Procesado</option>
                        <option value="enviado" <?php echo isset($_GET['status']) && $_GET['status'] == 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                        <option value="cancelado" <?php echo isset($_GET['status']) && $_GET['status'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="user_type">Tipo de Usuario:</label>
                    <select name="user_type" id="user_type" class="form-control">
                        <option value="">Todos</option>
                        <option value="consumidor" <?php echo isset($_GET['user_type']) && $_GET['user_type'] == 'consumidor' ? 'selected' : ''; ?>>Consumidor Final</option>
                        <option value="empresa" <?php echo isset($_GET['user_type']) && $_GET['user_type'] == 'empresa' ? 'selected' : ''; ?>>Empresa</option>
                    </select>
                </div>
                <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filtrar</button>
</div>
            </div>
<div class="text-right mb-3"></div>
            </form>

        <!-- Tabla de pedidos -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
            <thead class="thead-dark">
    <tr>
        <th>Total</th>
        <th>Estado</th>
        <th>Tipo de Usuario</th> <!-- Agregar esta columna -->
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Teléfono</th>
        <th>Email form</th>
        <th>Razón Social</th>
        <th>CUIT</th>
        <th>Contacto</th>
        <th>Items</th>
        <th>Acciones</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($pedidos as $pedido): ?>
    <tr>
        <td><?php echo number_format($pedido['total'], 2); ?> USD</td>
        <td>
            <form method="POST" action="cambiar_estado.php" class="form-inline">
                <input type="hidden" name="pedido_id" value="<?php echo htmlspecialchars($pedido['id']); ?>">
                <select name="estado" class="form-control form-control-sm">
                    <option value="pendiente" <?php echo $pedido['status'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="procesado" <?php echo $pedido['status'] == 'procesado' ? 'selected' : ''; ?>>Procesado</option>
                    <option value="enviado" <?php echo $pedido['status'] == 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                    <option value="cancelado" <?php echo $pedido['status'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                </select>
                <button type="submit" class="btn btn-warning btn-sm ml-2">Actualizar</button>
            </form>
        </td>
        <td><?php echo htmlspecialchars($pedido['user_type']); ?></td> <!-- Mostrar tipo de usuario -->
        <td><?php echo htmlspecialchars($pedido['nombre']); ?></td>
        <td><?php echo htmlspecialchars($pedido['apellido']); ?></td>
        <td><?php echo htmlspecialchars($pedido['telefono']); ?></td>
        <td><?php echo htmlspecialchars($pedido['email']); ?></td>
        <td><?php echo htmlspecialchars($pedido['razon_social']); ?></td>
        <td><?php echo htmlspecialchars($pedido['cuit']); ?></td>
        <td><?php echo htmlspecialchars($pedido['persona_contacto']); ?></td>
        <td><?php echo htmlspecialchars($pedido['items_count']); ?></td>
        <td>
            <a href="ver_pedido.php?id=<?php echo $pedido['id']; ?>" class="btn btn-primary btn-sm">Ver más</a>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
            </table>
        </div>

        <!-- Paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?php echo $currentPage == 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>&status=<?php echo htmlspecialchars($_GET['status'] ?? ''); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo htmlspecialchars($_GET['status'] ?? ''); ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php echo $currentPage == $totalPages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>&status=<?php echo htmlspecialchars($_GET['status'] ?? ''); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>