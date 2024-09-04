<?php
// Incluir el archivo de configuración de la base de datos y verificación de sesión 
include 'config/database.php';
include 'config/checksession.php';

// Verificar si el usuario ha iniciado la maldita sesión
if (!isset($_SESSION['user_id'])) {
    // Redirigir a login.php si no se encuentra user_id en la sesión 
    header("Location: login.php");
    exit();
}

// Obtener el user_id de la sesión
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
        
        // Si es una solicitud AJAX, no redirigir
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            exit(); // Terminar la ejecución para evitar cualquier HTML adicional
        } else {
            // Redirigir para evitar reenvíos de formularios en solicitudes normales
            header("Location: carrito.php");
            exit();
        }
    }
}

?>

<?php include 'assets/includes/head.php';?>
<style>
    /* Estilos generales */
    body {
        background-color: #f8f9fa;
        color: #333;
    }

    h1 {
        font-weight: bold;
        color: #343a40;
        margin-bottom: 20px;
    }
    hr{
        display: none;
    }
    .container2 {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Estilo del carrito */
    .cart-header {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 2px solid #dee2e6;
        font-weight: bold;
        color: #555;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        border-bottom: 1px solid #dee2e6;
        padding: 15px 0;
    }

    .cart-item img {
        max-width: 200px;
        max-height: 200px;
        width: auto;
        height: auto;
        border-radius: 35px;
        margin-bottom: 10px;
    }

    .cart-item h4 {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .cart-item p {
        margin: 0 0 5px;
    }

    .cart-item .form-inline {
        display: flex;
        align-items: center;
    }

    .cart-item .input-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .number-inside {
    width: 35px;
    height: 35px;
    line-height: 50px; /* Asegura que el texto esté centrado verticalmente */
    border-radius: 50%; /* Círculo perfecto */
    border: 1px solid #343a40;
    background: none;
    font-size: 18px;
    font-weight: 500;
    text-align: center; /* Centrar el número horizontalmente */
    padding: 0; /* Elimina padding interno */
    margin: 0; /* Elimina cualquier margen */
    appearance: none; /* Elimina el estilo predeterminado del input */
    -moz-appearance: textfield; /* Elimina los spinners en Firefox */
    }

    .number-inside::-webkit-outer-spin-button,
    .number-inside::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .cart-item .btn {
        padding: 5px 10px;
    }

    .cart-item .text-right {
        text-align: right;
        margin-top: 10px;
        font-weight: 700;
    }

    .right {
        text-align: right;
        display: flex;
        justify-content: flex-end;
    }
    
    .btn {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .delete-g{
        display: flex;
        justify-content: flex-end;
        margin-top: 60px;
    }

    /* Botón de finalizar pago :D*/
    .btn-primary {
        background-color: #D10024;
        border: none;
        padding: 10px 20px;
        color: white;
        text-transform: uppercase;
        font-weight: bold;
        border-radius: 2.25rem;
        transition: background-color 0.3s ease;
        max-width: 300px;
        min-width: 200px;
    }

    .btn-primary:hover {
        background-color: #A8001D;
    }
    
    .plus, .minus{
        background: none !important;
        border: none !important;
        margin-left: -16px;
    }
    .img-tt{
        display: flex;
    }
    .title-marc{
        padding: 10px 15px;
    }
    .title-marc h4 {
        white-space: normal; /* Permite que el texto haga salto de línea en pantallas más grandes */
        overflow: visible; /* Muestra todo el contenido del texto */
        text-overflow: clip; /* Elimina los puntos suspensivos si el texto cabe en el contenedor */          
}
    /* Responsive */
    @media (max-width: 992px) {
    .cart-header {
        display: none;
    }
    hr{
        display: block;
        border-top:2px solid #dee2e6;
    }
    .title-marc {
        padding: 10px 15px;
        width: calc(100% - 45px);
    }
    .title-marc h4 {
        white-space: nowrap;        /* No permite que el texto haga salto de línea */
        overflow: hidden;           /* Oculta el texto que no cabe en el contenedor */
        text-overflow: ellipsis;    /* Muestra los puntos suspensivos (...) al final del texto */
        max-width: 100%;  /* Asegura que el ancho máximo del contenedor se ajuste al 100% de su contenedor padre */
    }
    .cart-item .col-md-6,
    .cart-item .col-md-2,
    .cart-item .text-right {
        width: 100%;
        margin-bottom: 10px;
    }

    .cart-item img {
        max-width: 80px;
        margin-bottom: 15px;
        border-radius: 10px;
    }
    
    .cart-item h4 {
        font-size: 1.6rem;
        margin-bottom: 10px;
    }

    .cart-item .text-right {
        margin-top: 10px;
    }

    .cart-item .btn {
        width: 45px;
        margin-top: 5px;
    }

    .cart-item .input-group {
        justify-content: space-between;
        width: 70%;
    }

    .delete-g {
        justify-content: flex-end;
        margin-top: 10px;
    }

    .btn-primary {
        width: 100%;
        margin-top: 20px;
    }
    button.plus {
        margin-left: -35px !important;
    }
    .cart-item .form-inline {
        justify-content: center;
    }
}
</style>
</head>
<body>
    <!-- HEADER -->
    <?php include 'assets/includes/header.php';?>
    <!-- HEADER -->
     <div class="section">
    <div class="container2 mt-4">
        <h1>Mi Carrito</h1>
        <hr>

        <!-- Header del carrito -->
        <div class="cart-header">
            <div class="col-md-6">Producto</div>
            <div class="col-md-2">Precio</div>
            <div class="col-md-2">Cantidad</div>
            <div class="col-md-2 text-right">Total</div>
        </div>
        <?php if (!empty($items)): ?>
            <div class="list-group">
                <?php foreach ($items as $item): ?>
                    <div class="cart-item" id="cart-item-<?php echo $item['id']; ?>">
                        <div class="col-md-6 img-tt">
                            <img src="assets/images/<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            <div class="title-marc">
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p>Marca: <?php echo htmlspecialchars($item['brand_name']); ?></p>
                            </div>
                        </div>
                        <div class="col-md-2 title-marc">
                            <p class="precio" id="price-<?php echo $item['id']; ?>">$<?php echo number_format($item['price'], 2); ?></p>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="button" class="minus" onclick="updateQuantity(<?php echo $item['id']; ?>, -1)"><span class="material-symbols-outlined">remove</span></button>
                                </span>
                                <input type="number" name="quantity" class="number-inside" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="0" max="99" readonly id="quantity-<?php echo $item['id']; ?>">
                                <span class="input-group-btn">
                                    <button type="button" class="plus" onclick="updateQuantity(<?php echo $item['id']; ?>, 1)"><span class="material-symbols-outlined">add</span></button>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2 text-right">
                            <p id="total-<?php echo $item['id']; ?>">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                            <form method="POST" action="carrito.php" class="d-inline delete-g">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                <button type="submit" name="delete_product" class="btn btn-danger ml-2"><span class="material-symbols-outlined">delete</span></button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="cart-summary">
                <small id="total-it"><?php echo $total_items; ?> Item(s)</small>
                <h5>SUBTOTAL: <span id="subtotal">$<?php echo number_format($total, 2); ?></span></h5>
            </div>
            <div class="mt-3 right">
                <a href="checkout.php" class="btn btn-primary">Finalizar pago</a>
            </div>
        <?php else: ?>
            <br>
            <div class="alert alert-info" role="alert">
                 Tu carrito está vacío.
            </div>
        <?php endif; ?>
    </div>
    </div>
    <!-- Footer -->
    <?php include 'assets/includes/footer.php';?>
    <!-- /FOOTER -->
    
</body>
</html>
