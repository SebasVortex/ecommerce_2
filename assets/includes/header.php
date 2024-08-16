<?php
// Asegúrate de iniciar la sesión
include 'config/database.php';
include 'config/checksession.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    try {
        // Obtener los productos del carrito del usuario desde la base de datos
        $query = "SELECT p.id, p.name, p.price, p.imagen, c.quantity 
                  FROM carrito c 
                  JOIN productos p ON c.product_id = p.id 
                  WHERE c.user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calcular el total
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    } catch (PDOException $e) {
        // Manejo de errores
        error_log('Error en la consulta: ' . $e->getMessage());
        $cart_items = [];
        $total = 0;
    }
} else {
    // Si el usuario no está logueado, el carrito está vacío
    $cart_items = [];
    $total = 0;
}
// Consultar todas las categorías
$stmt = $conn->prepare("SELECT name, id FROM categorias"); // Prepara una consulta SQL para seleccionar todas las categorías
$stmt->execute(); // Ejecuta la consulta
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtiene todos los resultados en un array asociativo
?>
<!-- HEADER -->
<header>
    <!-- TOP HEADER -->
    <div id="top-header">
        <div class="container">
            <ul class="header-links pull-left">
                <li><a href="#"><i class="fa fa-phone"></i> (+54)11 4488 4489</a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i> ventas@sistemasenergeticos.com.ar</a></li>
                <li><a href="#"><i class="fa fa-map-marker"></i> Av. Díaz Vélez 1240 (C.P. 1702)Ciudadela, Buenos Aires, Argentina</a></li>
            </ul>
            <ul class="header-links pull-right">
                <li><a href="userpanel.php"><i class="fa fa-user-o"></i> Mi cuenta</a></li>
                <li><a href="config/logout.php"><i class="fa fa-user-o"></i> Cerrar Sesion</a></li>
            </ul>
        </div>
    </div>
    <!-- /TOP HEADER -->

    <!-- MAIN HEADER -->
    <div id="header">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- LOGO -->
                <div class="col-md-3">
                    <div class="header-logo">
                        <a href="index.php" class="logo">
                            <img src="assets/images/logomenu.png" alt="">
                        </a>
                    </div>
                </div>
                <!-- /LOGO -->

                <!-- SEARCH BAR -->
                <div class="col-md-6">
                    <div class="header-search">
                        <form style="display: flex;">
                            <select class="input-select">
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?= htmlspecialchars($categoria['id']); ?>">
                                    <?= htmlspecialchars($categoria['name']); ?>
                                </option>
                            <?php endforeach; ?>
                            </select>
                            <input class="input" placeholder="Buscar productos...">
                            <button class="search-btn">Buscar</button>
                        </form>
                    </div>
                </div>
                <!-- /SEARCH BAR -->

                <!-- ACCOUNT -->
                <div class="col-md-3 clearfix">
                    <div class="header-ctn">
                        <!-- Wishlist 
                        <div>
                            <a href="#">
                                <i class="fa fa-heart-o"></i>
                                <span>Favoritos</span>
                                <div class="qty">2</div>
                            </a>
                        </div>
                        -->
                        <!-- /Wishlist -->

                        <!-- Cart -->
                        <div class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-shopping-cart"></i>
                                <span>Carrito</span>
                                <div class="qty"><?php echo isset($_SESSION['user_id']) ? count($cart_items) : 0; ?></div>
                            </a>
                            <div class="cart-dropdown">
                                <div class="cart-list">
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <?php if (!empty($cart_items)): ?>
                                            <?php foreach ($cart_items as $item): ?>
                                                <div class="product-widget">
                                                    <div class="product-img">
                                                        <img src="assets/images/<?php echo htmlspecialchars($item['imagen']); ?>" alt="">
                                                    </div>
                                                    <div class="product-body">
                                                        <h3 class="product-name"><a href="#"><?php echo htmlspecialchars($item['name']); ?></a></h3>
                                                        <h4 class="product-price"><span class="qty"><?php echo htmlspecialchars($item['quantity']); ?>x</span>$<?php echo number_format($item['price'], 2); ?></h4>
                                                    </div>
                                                    <button class="delete"><i class="fa fa-close"></i></button>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p>Tu carrito está vacío</p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p>Inicia sesión para ver los productos de tu carrito.</p>
                                    <?php endif; ?>
                                </div>
                                <div class="cart-summary">
                                    <small><?php echo isset($_SESSION['user_id']) ? count($cart_items) : 0; ?> Item(s) selected</small>
                                    <h5>SUBTOTAL: $<?php echo isset($_SESSION['user_id']) ? number_format($total, 2) : '0.00'; ?></h5>
                                </div>
                                <div class="cart-btns">
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <a href="carrito.php">Ver carrito</a>
                                        <a href="checkout.php">Finalizar <i class="fa fa-arrow-circle-right"></i></a>
                                    <?php else: ?>
                                        <a href="login.php">Inicia sesión para finalizar compra</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!-- /Cart -->

                        <!-- Menu Toggle -->
                        <div class="menu-toggle">
                            <a href="#">
                                <i class="fa fa-bars"></i>
                                <span>Menu</span>
                            </a>
                        </div>
                        <!-- /Menu Toggle -->
                    </div>
                </div>
                <!-- /ACCOUNT -->
            </div>
            <!-- row -->
        </div>
        <!-- container -->
    </div>
    <!-- /MAIN HEADER -->
</header>
<!-- /HEADER -->
 
 		<!-- NAVIGATION -->
         <nav id="navigation">
			<!-- container -->
			<div class="container">
				<!-- responsive-nav -->
				<div id="responsive-nav">
					<!-- NAV -->
					<ul class="main-nav nav navbar-nav">
						<li class="active"><a href="#">Home</a></li>
						<li><a href="#">Hot Deals</a></li>
						<li><a href="#">Categories</a></li>
						<li><a href="#">Laptops</a></li>
						<li><a href="#">Smartphones</a></li>
						<li><a href="#">Cameras</a></li>
						<li><a href="#">Accessories</a></li>
					</ul>
					<!-- /NAV -->
				</div>
				<!-- /responsive-nav -->
			</div>
			<!-- /container -->
		</nav>
		<!-- /NAVIGATION -->