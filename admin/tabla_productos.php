<?php
// Conectar a la base de datos
include('../config/database.php');

// Recuperar todos los productos
$productos = [];
$stmt = $conn->prepare("
    SELECT p.*, m.name AS brand_name
    FROM productos p
    LEFT JOIN marcas m ON p.brand_id = m.id
");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <!-- Incluir Bootstrap CSS -->
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
    <div class="container mt-5">
        <h1 class="text-left mb-4">Lista de Productos</h1>
        <div class="text-right mb-3">
            <button onclick="location.href='admin_producto.php'" class="btn btn-success">Agregar Nuevo Producto</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['id']); ?></td>
                            <td><?php echo htmlspecialchars($producto['name']); ?></td>
                            <td><?php echo htmlspecialchars($producto['brand_name']); ?></td>
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

    <!-- Incluir Bootstrap JS y dependencias de Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
