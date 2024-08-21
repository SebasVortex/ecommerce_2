<?php
// Asegúrate de iniciar la sesión
include 'config/database.php';
include 'config/checksession.php';

// Manejar la eliminación de productos del carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && isset($_SESSION['user_id'])) {
    $delete_id = $_POST['delete_id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Eliminar el producto del carrito en la base de datos
        $query = "DELETE FROM carrito WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $delete_id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir o recargar la página después de eliminar
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        error_log('Error al eliminar el producto: ' . $e->getMessage());
    }
}

// Código existente para obtener y mostrar los productos del carrito
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
$stmt = $conn->prepare("SELECT name, id FROM categorias");
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Obtener el precio máximo de la base de datos
$queryMaxPrice = "SELECT MAX(price) FROM productos";
$stmtMaxPrice = $conn->prepare($queryMaxPrice);
$stmtMaxPrice->execute();
$maxPrice = $stmtMaxPrice->fetchColumn();

// Obtener las marcas y las categorías disponibles
$queryBrands = "SELECT * FROM marcas";
$stmtBrands = $conn->prepare($queryBrands);
$stmtBrands->execute();
$brands = $stmtBrands->fetchAll(PDO::FETCH_ASSOC);

$queryCategories = "SELECT * FROM categorias";
$stmtCategories = $conn->prepare($queryCategories);
$stmtCategories->execute();
$categorias = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);

// Obtener las marcas, categorías y precios seleccionados desde el formulario
$selectedBrands = isset($_GET['brand']) ? $_GET['brand'] : [];
$selectedCategories = isset($_GET['category']) ? $_GET['category'] : [];
$priceMin = isset($_GET['price_min']) ? $_GET['price_min'] : 0;
$priceMax = isset($_GET['price_max']) ? $_GET['price_max'] : $maxPrice;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Construir la consulta SQL
$query = "SELECT p.*, m.name AS brand_name, c.name AS category_name
          FROM productos p
          LEFT JOIN marcas m ON p.brand_id = m.id
          LEFT JOIN categorias c ON p.category_id = c.id";

$conditions = [];
$params = [];

// Añadir condiciones para búsqueda de texto
if (!empty($searchTerm)) {
    $conditions[] = "p.name LIKE ?";
    $params[] = '%' . $searchTerm . '%';
}

// Añadir condiciones para marcas
if (!empty($selectedBrands)) {
    $placeholders = rtrim(str_repeat('?,', count($selectedBrands)), ',');
    $conditions[] = "p.brand_id IN ($placeholders)";
    $params = array_merge($params, $selectedBrands);
}

// Añadir condiciones para categorías
if (!empty($selectedCategories)) {
    $placeholders = rtrim(str_repeat('?,', count($selectedCategories)), ',');
    $conditions[] = "p.category_id IN ($placeholders)";
    $params = array_merge($params, $selectedCategories);
}

// Añadir condiciones para precio
if ($priceMin !== null) {
    $conditions[] = "p.price >= ?";
    $params[] = $priceMin;
}

if ($priceMax !== null) {
    $conditions[] = "p.price <= ?";
    $params[] = $priceMax;
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

// Ordenar los productos aleatoriamente
$query .= " ORDER BY RAND()";

$stmt = $conn->prepare($query);
$stmt->execute($params);
$productostore = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <input id="search-input" style=" width: 100%;" name="search" class="input" placeholder="Buscar productos...">
                    </form>
                </div>
                <!-- Resultados de búsqueda -->
                <div id="results-container" style=" max-height: 500px;overflow-y: auto; position: absolute; background: white; z-index: 9999;"></div>
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
                                                        <h3 class="product-name"><a href="product_detalle.php?id=<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['name']); ?></a></h3>
                                                        <h4 class="product-price"><span class="qty"><?php echo htmlspecialchars($item['quantity']); ?>x</span>$<?php echo number_format($item['price'], 2); ?></h4>
                                                    </div>
                                                    <form method="POST" action="">
                                                        <input type="hidden" name="delete_id" value="<?php echo $item['id']; ?>">
                                                        <button class="delete" type="submit"><i class="fa fa-close"></i></button>
                                                    </form>
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
                                    <small><?php echo isset($_SESSION['user_id']) ? count($cart_items) : 0; ?> Item(s)</small>
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
						<li class="active"><a href="index.php">Inicio</a></li>
						<li><a href="#">Ofertas</a></li>
						<li><a href="store.php">Categorias</a></li>
						<li><a href="store.php?category%5B%5D=11">Paneles Solares</a></li>
						<li><a href="store.php?category%5B%5D=9">Baterias de litio</a></li>
						<li><a href="store.php?category%5B%5D=1">Sistemas hibridos</a></li>
					</ul>
					<!-- /NAV -->
				</div>
				<!-- /responsive-nav -->
			</div>
			<!-- /container -->
		</nav>
		<!-- /NAVIGATION -->
         