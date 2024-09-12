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
include('../config/database.php');

// Variables para filtros y orden
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC'; // Orden por defecto
$brandFilter = isset($_GET['brand']) ? $_GET['brand'] : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

// Definir variables para la paginación
$productosPorPagina = 15; // Número de productos por página
$paginaActual = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
$offset = ($paginaActual - 1) * $productosPorPagina; // Calcular el offset

// Consulta base
$query = "
    SELECT p.*, m.name AS brand_name, c.name AS category_name
    FROM productos p
    LEFT JOIN marcas m ON p.brand_id = m.id
    LEFT JOIN categorias c ON p.category_id = c.id
    WHERE 1=1
";

// Aplicar filtros de marca y categoría si están seleccionados
if ($brandFilter) {
    $query .= " AND p.brand_id = :brand_id";
}
if ($categoryFilter) {
    $query .= " AND p.category_id = :category_id";
}

// Añadir orden
$query .= " ORDER BY p.created_at $order";

// Modificar la consulta para incluir LIMIT y OFFSET para la paginación
$query .= " LIMIT :limit OFFSET :offset";

// Preparar y ejecutar la consulta con paginación
$stmt = $conn->prepare($query);

// Vincular parámetros de filtro si existen
if ($brandFilter) {
    $stmt->bindParam(':brand_id', $brandFilter, PDO::PARAM_INT);
}
if ($categoryFilter) {
    $stmt->bindParam(':category_id', $categoryFilter, PDO::PARAM_INT);
}

// Vincular parámetros de paginación
$stmt->bindParam(':limit', $productosPorPagina, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener el número total de productos (sin LIMIT ni OFFSET)
$totalProductos = $conn->query("
    SELECT COUNT(*) 
    FROM productos p
    LEFT JOIN marcas m ON p.brand_id = m.id
    LEFT JOIN categorias c ON p.category_id = c.id
    WHERE 1=1
    " . ($brandFilter ? " AND p.brand_id = $brandFilter" : "") . 
    ($categoryFilter ? " AND p.category_id = $categoryFilter" : "")
)->fetchColumn();

// Calcular el total de páginas
$totalPaginas = ceil($totalProductos / $productosPorPagina);

// Obtener todas las marcas y categorías para los selects de filtro
$marcas = $conn->query("SELECT * FROM marcas")->fetchAll(PDO::FETCH_ASSOC);
$categorias = $conn->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmDelete(productId) {
            if (confirm("¿Estás seguro de que quieres eliminar este producto?")) {
                window.location.href = 'eliminar_producto.php?id=' + productId;
            }
        }
    </script>
</head>
<body class="bg-light">
    <?php include('assets/menu.php'); ?>
    <div class="container mt-5" style="max-width: 1250px !important;">
        <h1 class="text-left mb-4">Lista de Productos</h1>

        <!-- Formulario de Filtros y Ordenación -->
        <form method="GET" class="mb-4">
            <div class="form-row align-items-end">
                <!-- Ordenar por fecha -->
                <div class="col-auto">
                    <label for="order">Ordenar por:</label>
                    <select name="order" id="order" class="form-control">
                        <option value="DESC" <?php if($order == 'DESC') echo 'selected'; ?>>Más Reciente</option>
                        <option value="ASC" <?php if($order == 'ASC') echo 'selected'; ?>>Más Antiguo</option>
                    </select>
                </div>

                <!-- Filtrar por marca -->
                <div class="col-auto">
                    <label for="brand">Marca:</label>
                    <select name="brand" id="brand" class="form-control">
                        <option value="">Todas</option>
                        <?php foreach ($marcas as $marca): ?>
                            <option value="<?php echo $marca['id']; ?>" <?php if($brandFilter == $marca['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($marca['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filtrar por categoría -->
                <div class="col-auto">
                    <label for="category">Categoría:</label>
                    <select name="category" id="category" class="form-control">
                        <option value="">Todas</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['id']; ?>" <?php if($categoryFilter == $categoria['id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($categoria['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                </div>
            </div>
        </form>

        <div class="text-right mb-3">
            <button onclick="location.href='admin_producto.php'" class="btn btn-success">Agregar Nuevo Producto</button>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['name']); ?></td>
                            <td><?php echo htmlspecialchars($producto['brand_name']); ?></td>
                            <td><?php echo htmlspecialchars($producto['category_name']); ?></td>
                            <td>$<?php echo number_format($producto['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($producto['stock']); ?></td>
                            <td>
                                <a href="admin_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $producto['id']; ?>)">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<!-- Controles de paginación -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $paginaActual - 1; ?>&order=<?php echo $order; ?>&brand=<?php echo $brandFilter; ?>&category=<?php echo $categoryFilter; ?>">Anterior</a>
        </li>
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <li class="page-item <?php echo ($paginaActual == $i) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&order=<?php echo $order; ?>&brand=<?php echo $brandFilter; ?>&category=<?php echo $categoryFilter; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $paginaActual + 1; ?>&order=<?php echo $order; ?>&brand=<?php echo $brandFilter; ?>&category=<?php echo $categoryFilter; ?>">Siguiente</a>
        </li>
    </ul>
</nav>

    <!-- Incluir Bootstrap JS y dependencias de Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
