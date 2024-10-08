<?php
include 'config/database.php';
include 'config/checksession.php';

// Obtener el precio máximo de la base de datos
$queryMaxPrice = "SELECT MAX(price) FROM productos";
$stmtMaxPrice = $conn->prepare($queryMaxPrice);
$stmtMaxPrice->execute();
$maxPrice = $stmtMaxPrice->fetchColumn();

// Obtener las marcas, categorías y precios seleccionados desde el formulario
$selectedBrands = isset($_GET['brand']) ? array_map('intval', $_GET['brand']) : [];
$selectedCategories = isset($_GET['category']) ? array_map('intval', $_GET['category']) : [];
$priceMin = isset($_GET['price_min']) ? intval($_GET['price_min']) : 0;
$priceMax = isset($_GET['price_max']) ? intval($_GET['price_max']) : $maxPrice;
$searchTerm = isset($_GET['search']) ? '%' . htmlspecialchars($_GET['search']) . '%' : '';

// Obtener el número de productos por página del parámetro, por defecto es 12
$productos_por_pagina = isset($_GET['items_per_page']) ? intval($_GET['items_per_page']) : 12;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $productos_por_pagina;

// Construir la consulta SQL
$query = "SELECT p.*, m.name AS brand_name, c.name AS category_name
          FROM productos p
          LEFT JOIN marcas m ON p.brand_id = m.id
          LEFT JOIN categorias c ON p.category_id = c.id";

$conditions = [];
$params = [];

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

// Añadir condición para búsqueda por similitud en nombre y descripción
if (!empty($searchTerm)) {
    $conditions[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

// Ordenar los productos aleatoriamente (considera cambiar esto si es lento)
$query .= " ORDER BY RAND()";

// Agregar paginación
$query .= " LIMIT $productos_por_pagina OFFSET $offset";

try {
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $productostore = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener el total de productos para la paginación
    $queryTotal = "SELECT COUNT(*) FROM productos p";
    if (!empty($conditions)) {
        $queryTotal .= " WHERE " . implode(' AND ', $conditions);
    }
    $stmtTotal = $conn->prepare($queryTotal);
    $stmtTotal->execute($params);
    $total_productos = $stmtTotal->fetchColumn();

    $total_paginas = ceil($total_productos / $productos_por_pagina);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Funciones para contar productos por marca y categoría
function getProductCountByBrand($brandId) {
    global $conn;
    $query = "SELECT COUNT(*) FROM productos WHERE brand_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$brandId]);
    return $stmt->fetchColumn();
}

function getProductCountByCategory($categoryId) {
    global $conn;
    $query = "SELECT COUNT(*) FROM productos WHERE category_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$categoryId]);
    return $stmt->fetchColumn();
}

// Funciones para obtener el nombre de la categoría o la marca
function getCategoryName($categoryId) {
    global $conn;
    $query = "SELECT name FROM categorias WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$categoryId]);
    return $stmt->fetchColumn();
}

function getBrandName($brandId) {
    global $conn;
    $query = "SELECT name FROM marcas WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$brandId]);
    return $stmt->fetchColumn();
}
?>


	<?php include 'assets/includes/head.php';?>
		<body>
			<!-- HEADER -->
			<?php include 'assets/includes/header.php';?>
				<!-- /MAIN HEADER -->
			</header>
			<!-- /HEADER -->

			<!-- BREADCRUMB -->
			<div id="breadcrumb" class="section">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<div class="col-md-12">
							<ul class="breadcrumb-tree">
								<li><a href="index.php">Inicio</a></li>
								<li><a href="store.php">Categorias</a></li>
								<?php if (!empty($categoryFilter)): ?>
									<li><a href="productos.php?category[]=<?php echo implode('&category[]=', $categoryFilter); ?>"><?php echo htmlspecialchars(getCategoryName($categoryFilter[0])); ?></a></li>
								<?php endif; ?>
								<?php if (!empty($brandFilter)): ?>
									<li><a href="productos.php?brand[]=<?php echo implode('&brand[]=', $brandFilter); ?>"><?php echo htmlspecialchars(getBrandName($brandFilter[0])); ?></a></li>
								<?php endif; ?>
								<li class="active">Productos</li>
							</ul>
						</div>
					</div>
					<!-- /row -->
				</div>
				<!-- /container -->
			</div>
			<!-- /BREADCRUMB -->

			<!-- SECTION -->
			<div class="section">
				<!-- container -->
				<div class="container">
					<!-- row -->
					<div class="row">
						<!-- ASIDE -->
						<div id="aside" class="col-md-3">
							<!-- aside Widget -->
							<div class="aside">
								<h3 class="aside-title">Categorías</h3>
								<div class="checkbox-filter">
									<form id="category-filter-form" method="GET" action="">
										<?php
										// Obtener las categorías desde la base de datos
										$query = "SELECT id, name FROM categorias";
										$stmt = $conn->prepare($query);
										$stmt->execute();
										$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

										foreach ($categories as $category):
											$categoryId = htmlspecialchars($category['id']);
											$categoryName = htmlspecialchars($category['name']);
										?>
											<div class="input-checkbox">
												<input type="checkbox" id="category-<?php echo $categoryId; ?>" name="category[]" value="<?php echo $categoryId; ?>" onchange="document.getElementById('category-filter-form').submit();">
												<label for="category-<?php echo $categoryId; ?>">
													<span></span>
													<?php echo $categoryName; ?>
													<small>(<?php echo getProductCountByCategory($categoryId); ?>)</small>
												</label>
											</div>
										<?php endforeach; ?>
									</form>
								</div>
							</div>
							<!-- /aside Widget -->

						<!-- aside Widget 
						<div class="aside">
							<h3 class="aside-title">Price</h3>
							<div class="price-filter">
								<div id="price-slider" class="noUi-connect"></div>
								<div class="input-number price-min">
									<input id="price-min" type="number" readonly>
								</div>
								<span>-</span>
								<div class="input-number price-max">
									<input id="price-max" type="number" readonly>
								</div>
							</div>
						</div>
						-->
						<!-- /aside Widget -->



						<!-- aside Widget -->
						<div class="aside">
							<h3 class="aside-title">Marcas</h3>
							<div class="checkbox-filter">
								<form id="brand-filter-form" method="GET" action="">
									<?php
									// Obtener las marcas desde la base de datos
									$query = "SELECT id, name FROM marcas";
									$stmt = $conn->prepare($query);
									$stmt->execute();
									$brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

									foreach ($brands as $brand):
										$brandId = htmlspecialchars($brand['id']);
										$brandName = htmlspecialchars($brand['name']);
									?>
										<div class="input-checkbox">
											<input type="checkbox" id="brand-<?php echo $brandId; ?>" name="brand[]" value="<?php echo $brandId; ?>" onchange="document.getElementById('brand-filter-form').submit();">
											<label for="brand-<?php echo $brandId; ?>">
												<span></span>
												<?php echo $brandName; ?>
												<small>(<?php echo getProductCountByBrand($brandId); ?>)</small>
											</label>
										</div>
									<?php endforeach; ?>
								</form>
							</div>
						</div>
						<!-- /aside Widget -->
								
						</div>
						<!-- /ASIDE -->

						<!-- STORE -->
						<div id="store" class="col-md-9">
							<!-- store top filter -->
								<div class="store-filter clearfix">
									<div class="store-sort">
										<form method="GET" action="">

											<label>
												Mostrar: 
												<select class="input-select" name="items_per_page">
													<option value="20" <?php echo (isset($_GET['items_per_page']) && $_GET['items_per_page'] == '20') ? 'selected' : ''; ?>>20</option>
													<option value="50" <?php echo (isset($_GET['items_per_page']) && $_GET['items_per_page'] == '50') ? 'selected' : ''; ?>>50</option>
												</select>
											</label>

											<button type="submit" class="primary-btn">Filtrar</button>
										</form>
									</div>
									<ul class="store-grid">
										<li class="active"><i class="fa fa-th"></i></li>
										<!--<li><a href="#"><i class="fa fa-th-list"></i></a></li>-->
									</ul>
								</div>
								<!-- /store top filter -->


							<!-- store products -->
<div class="row" id="product-list">
    <?php
    $count = 0; // Contador de productos
    foreach ($productostore as $producto):

    ?>  
    <!-- product -->
    <div class="col-md-4 col-xs-6">
        <div class="product" data-price="<?php echo htmlspecialchars($producto['price']); ?>">
		<a class="product-img" href="product_detalle.php?id=<?php echo $producto['id']; ?>">
            <div class="product-img">
                <img src="assets/images/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['name']); ?>">
                <div class="product-label">
                    <?php if ($producto['discount'] > 0): ?>
                        <span class="sale">-<?php echo htmlspecialchars($producto['discount']); ?>%</span>
                    <?php endif; ?>
                    <?php if ($producto['new']): ?>
                        <span class="new">Nuevo <i class="fa-solid fa-exclamation fa-shake" style="--fa-animation-duration: 2s;"></i></span>
                    <?php endif; ?>
                </div>
            </div>
			</a>
            <div class="product-body">
                <p class="product-category"><?php echo htmlspecialchars($producto['category_name']); ?></p>
                <h3 class="product-name"><a href="product_detalle.php?id=<?php echo $producto['id']; ?>"><?php echo htmlspecialchars($producto['brand_name']); ?> - <?php echo htmlspecialchars($producto['name']); ?></a></h3>
                <h4 class="product-price">$<?php echo number_format($producto['price'], 2); ?>
                    <del class="product-old-price">$<?php echo number_format($producto['old_price'], 2); ?></del>
                </h4>
                <div class="product-rating" style="display: none;">
                    <?php for ($i = 0; $i < $rating; $i++): ?>
                        <i class="fa fa-star"></i>
                    <?php endfor; ?>
                </div>
                <div class="product-btns">
                    <button class="add-to-wishlist" onclick="addToCart(<?php echo htmlspecialchars($producto['id']); ?>)"><i class="fa-solid fa-cart-plus fa-flip" style="--fa-animation-duration: 3s;"></i><span class="tooltipp">Añadir carrito</span></button>
					<button class="quick-view" data-product-id="<?php echo $producto['id']; ?>">
														<i class="fa fa-eye fa-beat" style="--fa-animation-duration: 2s;"></i><span class="tooltipp">Ver mas</span>
													</button>
                </div>
            </div>
            <div class="add-to-cart">
                <button class="add-to-cart-btn" onclick="addToCart(<?php echo htmlspecialchars($producto['id']); ?>)"><i class="fa fa-shopping-cart"></i> Añadir al carrito</button>
            </div>
        </div>
    </div>
    <!-- /product -->

    <?php 
    $count++;
    // Insertar clearfix después de 2 productos en pantallas pequeñas y extra pequeñas
    if ($count % 2 == 0): ?>
        <div class="clearfix visible-sm visible-xs"></div>
    <?php endif; ?>
    
    <?php 
    // Insertar clearfix después de 3 productos en pantallas grandes y medianas
    if ($count % 3 == 0): ?>
        <div class="clearfix visible-lg visible-md"></div>
    <?php endif; ?>

    <?php endforeach; ?>
</div>
<!-- /store products -->



							<div class="store-filter clearfix">
								<span class="store-qty">Mostrando <?php echo min($offset + $productos_por_pagina, $total_productos); ?> de <?php echo $total_productos; ?> productos</span>
								<ul class="store-pagination">
									<?php if ($pagina_actual > 1): ?>
										<li><a href="?pagina=<?php echo $pagina_actual - 1; ?>"><i class="fa fa-angle-left"></i></a></li>
									<?php endif; ?>
									
									<?php for ($i = 1; $i <= $total_paginas; $i++): ?>
										<li class="<?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
											<a href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
										</li>
									<?php endfor; ?>
									
									<?php if ($pagina_actual < $total_paginas): ?>
										<li><a href="?pagina=<?php echo $pagina_actual + 1; ?>"><i class="fa fa-angle-right"></i></a></li>
									<?php endif; ?>
								</ul>
							</div>

					</div>
					<!-- /STORE -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

			<!-- PIE DE PÁGINA -->
			<?php include 'assets/includes/footer.php';?>
		<!-- /PIE DE PÁGINA -->

	</body>
</html>