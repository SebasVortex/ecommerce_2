<?php
// Incluir configuración y verificación de sesión
include 'config/database.php';
include 'config/checksession.php';

// Manejo de eliminación de productos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && isset($_SESSION['user_id'])) {
    $delete_id = $_POST['delete_id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Eliminar producto del carrito
        $query = "DELETE FROM carrito WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $delete_id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir para evitar reenvíos de formularios
        header("Location: carrito.php");
        exit;
    } catch (PDOException $e) {
        error_log('Error al eliminar el producto: ' . $e->getMessage());
    }
}

// Obtener productos del carrito
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    try {
        $query = "SELECT p.id, p.name, p.price, p.imagen, c.quantity 
                  FROM carrito c 
                  JOIN productos p ON c.product_id = p.id 
                  WHERE c.user_id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calcular total y número de artículos
        $total = 0;
        $total_items = 0;
        foreach ($cart_items as $item) {
            $total += $item['price'] * $item['quantity'];
            $total_items += $item['quantity'];
        }
    } catch (PDOException $e) {
        error_log('Error en la consulta: ' . $e->getMessage());
        $cart_items = [];
        $total = 0;
        $total_items = 0;
    }
} else {
    $cart_items = [];
    $total = 0;
    $total_items = 0;
}

// Consultar categorías
$stmt = $conn->prepare("SELECT name, id FROM categorias");
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HEADER -->
<header>
    <!-- TOP HEADER -->
    <div id="top-header">
        <div class="container">
            <ul class="header-links pull-left">
                <li><a href="#"><i class="fa fa-phone"></i> (+54)11 4488 4489</a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i> ventas@sistemasenergeticos.com.ar</a></li>
                <li><a href="#"><i class="fa fa-map-marker"></i> Av. Díaz Vélez 1240 (C.P. 1702) Ciudadela, Buenos Aires, Argentina</a></li>
            </ul>
            <ul class="header-links pull-right">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="userpanel.php"><i class="fa fa-user-o"></i> Mi cuenta</a></li>
                    <li><a href="config/logout.php"><i class="fa fa-user-o"></i> Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php"><i class="fa fa-user-o"></i> Iniciar Sesión</a></li>
                    <li><a href="register.php"><i class="fa fa-user-o"></i> Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <!-- /TOP HEADER -->

    <!-- MAIN HEADER -->
    <div id="header">
        <div class="container">
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
                            <input id="search-input" style="width: 100%; border-radius: 25px;" name="search" class="input" placeholder="Buscar productos...">
                        </form>
                        <div id="results-container" style="width: 100%; max-width: 550px; max-height: 500px; overflow-y: auto; position: absolute; background: white; z-index: 9999; margin-left: auto; margin-right: auto;"></div>
                    </div>
                </div>
                <!-- /SEARCH BAR -->

                <!-- ACCOUNT -->
                <div class="col-md-3 clearfix">
                    <div class="header-ctn"  >
                    <!-- Cart -->
                    <div class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Carrito</span>
                            <div class="qty"><?php echo $total_items; ?></div>
                        </a>
                        <div class="cart-dropdown" id="carrito-contenido">
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
        </div>
    </div>
    <!-- /MAIN HEADER -->
</header>
<!-- /HEADER -->

<!-- NAVIGATION -->
<nav id="navigation">
    <div class="container">
        <div id="responsive-nav">
            <ul class="main-nav nav navbar-nav">
                <li class="active"><a href="index.php">Inicio</a></li>
                <li><a href="#">Ofertas</a></li>
                <li><a href="store.php">Categorías</a></li>
                <li><a href="store.php?category%5B%5D=11">Paneles Solares</a></li>
                <li><a href="store.php?category%5B%5D=9">Baterías de litio</a></li>
                <li><a href="store.php?category%5B%5D=1">Sistemas híbridos</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- /NAVIGATION -->
