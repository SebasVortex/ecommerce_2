<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "c1551887_calcu";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Manejo de la actualización del estado del cliente
if (isset($_GET['toggle_status_id'])) {
    $toggleStatusId = intval($_GET['toggle_status_id']);

    // Obtener el estado actual del cliente
    $stmt = $conn->prepare("SELECT status, solicitud_verificacion FROM clientes WHERE id = :id");
    $stmt->bindParam(':id', $toggleStatusId, PDO::PARAM_INT);
    $stmt->execute();
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    // Determinar el nuevo estado
    $newStatus = ($client['status'] === 'verificado') ? 'sin verificar' : 'verificado';

    // Actualizar el estado en la base de datos
    $updateStmt = $conn->prepare("UPDATE clientes SET status = :newStatus, solicitud_verificacion = NULL WHERE id = :id");
    $updateStmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
    $updateStmt->bindParam(':id', $toggleStatusId, PDO::PARAM_INT);
    $updateStmt->execute();

    // Redirigir para evitar resubmisión de formulario
    header("Location: admin_clientes.php");
    exit();
}

// Parámetros de paginación
$limit = 10; // Número de clientes por página
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Parámetros de filtrado
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$userTypeFilter = isset($_GET['user_type']) ? $_GET['user_type'] : '';
$solicitudFilter = isset($_GET['solicitud']) ? $_GET['solicitud'] : '';
$sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'DESC';

// Consultar clientes con filtros y paginación
$sql = "SELECT * FROM clientes WHERE 1=1";

if ($statusFilter) {
    $sql .= " AND status = :status";
}

if ($userTypeFilter) {
    $sql .= " AND tipo_usuario = :user_type";
}

if ($solicitudFilter !== '') {
    $sql .= " AND solicitud_verificacion = :solicitud";
}

$sql .= " ORDER BY created_at $sortOrder LIMIT :limit OFFSET :offset";

$stmt = $conn->prepare($sql);

if ($statusFilter) {
    $stmt->bindParam(':status', $statusFilter, PDO::PARAM_STR);
}

if ($userTypeFilter) {
    $stmt->bindParam(':user_type', $userTypeFilter, PDO::PARAM_STR);
}

if ($solicitudFilter !== '') {
    $stmt->bindParam(':solicitud', $solicitudFilter, PDO::PARAM_STR);
}

$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener la cantidad total de clientes
$totalStmt = $conn->query("SELECT COUNT(*) FROM clientes");
$totalClientes = $totalStmt->fetchColumn();

// Obtener la cantidad de pedidos por cliente
$order_counts = [];
$stmt = $conn->query("SELECT user_id, COUNT(*) as order_count FROM pedidos GROUP BY user_id");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $order_counts[$row['user_id']] = $row['order_count'];
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Clientes</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
   table{
    text-align: center;
   }

    </style>
</head>
<body class="bg-light">
<?php include('assets/menu.php'); ?>
<div class="container mt-5" style="max-width: 1250px !important;">
    <h2>Administración de Clientes</h2>

    <!-- Filtros -->
    <form method="GET" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="status">Estado</label>
                <select id="status" name="status" class="form-control">
                    <option value="">Todos</option>
                    <option value="verificado" <?php if ($statusFilter == 'verificado') echo 'selected'; ?>>Verificado</option>
                    <option value="sin verificar" <?php if ($statusFilter == 'sin verificar') echo 'selected'; ?>>Sin Verificar</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="user_type">Tipo de Usuario</label>
                <select id="user_type" name="user_type" class="form-control">
                    <option value="">Todos</option>
                    <option value="consumidor_final" <?php if ($userTypeFilter == 'consumidor_final') echo 'selected'; ?>>Consumidor Final</option>
                    <option value="empresa" <?php if ($userTypeFilter == 'empresa') echo 'selected'; ?>>Empresa</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="sort">Ordenar por Fecha</label>
                <select id="sort" name="sort" class="form-control">
                    <option value="DESC" <?php if ($sortOrder == 'DESC') echo 'selected'; ?>>Más Reciente</option>
                    <option value="ASC" <?php if ($sortOrder == 'ASC') echo 'selected'; ?>>Más Antiguo</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="solicitud">Solicitud de Verificación</label>
                <select id="solicitud" name="solicitud" class="form-control">
                    <option value="">Todos</option>
                    <option value="si" <?php if ($solicitudFilter == 'si') echo 'selected'; ?>>Sí</option>
                    <option value="no" <?php if ($solicitudFilter == 'no') echo 'selected'; ?>>No</option>
                </select>
            </div>

            <div class="form-group col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Tabla de Clientes -->
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
        <tr>
            <th>Creado</th>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Tipo de Usuario</th>
            <th>Email</th>
            <th>Status</th>
            <th>Solicito</th>
            <th>Pedidos</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?php echo substr($cliente['created_at'], 0, 10); ?></td>
                <td><?php echo $cliente['username']; ?></td>
                <td><?php echo $cliente['nombre']; ?></td>
                <td><?php echo $cliente['apellido']; ?></td>
                <td><?php echo $cliente['tipo_usuario']; ?></td>
                <td><?php echo $cliente['email']; ?></td>
                <td><?php echo $cliente['status']; ?><br>
                    <a href="admin_clientes.php?toggle_status_id=<?php echo $cliente['id']; ?>" class="btn btn-primary btn-sm">Cambiar Status</a>
                </td>
                <td><?php echo $cliente['solicitud_verificacion']; ?></td>
                <td>
                    <?php echo isset($order_counts[$cliente['id']]) ? $order_counts[$cliente['id']] : 0; ?>
                </td>
                <td>
                    <a href="admin_clientes.php?delete_id=<?php echo $cliente['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este cliente?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav>
        <ul class="pagination">
            <?php
            $totalPages = ceil($totalClientes / $limit);
            for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo htmlspecialchars($statusFilter); ?>&user_type=<?php echo htmlspecialchars($userTypeFilter); ?>&sort=<?php echo htmlspecialchars($sortOrder); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- Incluir Bootstrap JS y dependencias de Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>