<?php include 'config/database.php'; // Asegúrate de incluir el archivo de configuración de la base de datos
include 'config/checksession.php';

$user_id = $_SESSION['user_id'];

// Obtener los productos en el carrito desde la base de datos
$query = "SELECT p.id, p.name, p.price, p.imagen, m.name AS brand_name, c.quantity 
          FROM carrito c 
          JOIN productos p ON c.product_id = p.id 
          LEFT JOIN marcas m ON p.brand_id = m.id 
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Manejar la actualización de la cantidad de productos en el carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_quantity'])) {
        $product_id = $_POST['product_id'];
        $new_quantity = $_POST['quantity'];
        
        if ($new_quantity > 0) {
            $update_query = "UPDATE carrito SET quantity = ? WHERE user_id = ? AND product_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->execute([$new_quantity, $user_id, $product_id]);
        } else {
            // Si la cantidad es 0, eliminar el producto del carrito
            $delete_query = "DELETE FROM carrito WHERE user_id = ? AND product_id = ?";
            $stmt = $conn->prepare($delete_query);
            $stmt->execute([$user_id, $product_id]);
        }
        
        // Redirigir para evitar reenvíos de formularios
        header("Location: carrito.php");
        exit();
    }

    // Manejar la eliminación directa del producto
    if (isset($_POST['delete_product'])) {
        $product_id = $_POST['product_id'];
        $delete_query = "DELETE FROM carrito WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->execute([$user_id, $product_id]);

        // Redirigir para evitar reenvíos de formularios
        header("Location: carrito.php");
        exit();
    }
}
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
     <div class="section">
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
                                <p>Marca: <?php echo htmlspecialchars($item['brand_name']); ?></p>
                                <p>Precio: $<?php echo number_format($item['price'], 2); ?></p>
                                <form method="POST" action="carrito.php" class="form-inline d-inline">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="submit" name="update_quantity" class="btn btn-default" onclick="this.form.quantity.stepDown()">-</button>
                                        </span>
                                        <input type="number" name="quantity" class="form-control text-center" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="0" max="99" readonly>
                                        <span class="input-group-btn">
                                            <button type="submit" name="update_quantity" class="btn btn-default" onclick="this.form.quantity.stepUp()">+</button>
                                        </span>
                                    </div>
                                </form>
                                <!-- Formulario para eliminar el producto -->
                                <form method="POST" action="carrito.php" class="d-inline">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                    <button type="submit" name="delete_product" class="btn btn-danger ml-2">Eliminar</button>
                                </form>
                            </div>
                            <div class="col-md-4 text-right">
                                <p>Total: $<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-3">
                <a href="checkout.php" class="btn btn-primary">Finalizar pago</a>
            </div>
        <?php else: ?>
            <p>Tu carrito está vacío.</p>
        <?php endif; ?>
    </div>
    </div>
    <!-- PIE DE PÁGINA -->
    <?php include 'assets/includes/footer.php';?>
    <!-- /PIE DE PÁGINA -->
</body>
</html>
