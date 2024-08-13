<?php
session_start();

// Conectar a la base de datos
include('config/database.php');

// Verificar si se ha pasado un ID de producto
if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);

    // Verificar si el producto ya está en el carrito
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Añadir el producto al carrito (o actualizar la cantidad si ya está en el carrito)
    if (isset($_SESSION['carrito'][$productId])) {
        $_SESSION['carrito'][$productId]['quantity'] += 1;
    } else {
        // Obtener los detalles del producto
        $stmt = $conn->prepare("SELECT * FROM productos WHERE id = :id");
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Agregar el producto al carrito
        if ($product) {
            $_SESSION['carrito'][$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
                'imagen' => $product['imagen']
            ];
        }
    }


} else {
    // Redirigir a una página de error si no se pasa ningún ID
    header('Location: error.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .cart-item img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
        .cart-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Carrito de Compras</h1>
        <?php if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])): ?>
            <div class="list-group">
                <?php foreach ($_SESSION['carrito'] as $item): ?>
                    <div class="cart-item">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="assets/images/<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                            <div class="col-md-6">
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p>Precio: $<?php echo number_format($item['price'], 2); ?></p>
                                <p>Cantidad: <?php echo htmlspecialchars($item['quantity']); ?></p>
                            </div>
                            <div class="col-md-4 text-right">
                                <p>Total: $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                                <!-- Aquí podrías añadir opciones para modificar la cantidad o eliminar el producto -->
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-3">
                <a href="checkout.php" class="btn btn-primary">Proceder al Pago</a>
            </div>
        <?php else: ?>
            <p>Tu carrito está vacío.</p>
        <?php endif; ?>
    </div>
</body>
</html>
