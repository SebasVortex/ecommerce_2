<?php

include 'config/database.php'; // Asegúrate de incluir el archivo de configuración de la base de datos
include 'config/checksession.php';

$user_id = $_SESSION['user_id'];

// Obtener los productos en el carrito desde la base de datos
$query = "SELECT p.id, p.name, p.price, p.imagen, c.quantity 
          FROM carrito c 
          JOIN productos p ON c.product_id = p.id 
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'assets/includes/head.php';?>
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
		<!-- HEADER -->
		<?php include 'assets/includes/header.php';?>
		<!-- HEADER -->
    <div class="container mt-4">
        <h1>Carrito de Compras</h1>
        <?php if (!empty($items)): ?>
            <div class="list-group">
                <?php foreach ($items as $item): ?>
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
    		<!-- PIE DE PÁGINA -->
		<?php include 'assets/includes/footer.php';?>
		<!-- /PIE DE PÁGINA -->
</body>
</html>
