<?php
include 'config/database.php';
include 'config/checksession.php';

// Obtener el precio máximo de la base de datos
$queryMaxPrice = "SELECT MAX(price) FROM productos";
$stmtMaxPrice = $conn->prepare($queryMaxPrice);
$stmtMaxPrice->execute();
$maxPrice = $stmtMaxPrice->fetchColumn();

// Obtener las marcas y los precios seleccionados desde el formulario
$selectedBrands = isset($_GET['brand']) ? $_GET['brand'] : [];
$selectedCategories = isset($_GET['category']) ? $_GET['category'] : [];
$priceMin = isset($_GET['price_min']) ? $_GET['price_min'] : 0;
$priceMax = isset($_GET['price_max']) ? $_GET['price_max'] : $maxPrice;

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

if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

// Ordenar los productos aleatoriamente
$query .= " ORDER BY RAND()";

$stmt = $conn->prepare($query);
$stmt->execute($params);
$productostore = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
?>
<?php
// Obtener los filtros aplicados
$brandFilter = isset($_GET['brand']) ? $_GET['brand'] : [];
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : [];
?>

<?php
// Funciones para obtener el nombre de la categoría o la marca (debes definir estas funciones)
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

						<!-- aside Widget -->
						<div class="aside">
							<h3 class="aside-title">Price</h3>
							<div class="price-filter">
								<div id="price-slider"></div>
								<div class="input-number price-min">
									<input id="price-min" type="number">
									<span class="qty-up">+</span>
									<span class="qty-down">-</span>
								</div>
								<span>-</span>
								<div class="input-number price-max">
									<input id="price-max" type="number">
									<span class="qty-up">+</span>
									<span class="qty-down">-</span>
								</div>
							</div>
						</div>
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
									<label>
										Filtrar por:
										<select class="input-select">
											<option value="0">Popular</option>
											<option value="1">Position</option>
										</select>
									</label>

									<label>
										Show:
										<select class="input-select">
											<option value="0">20</option>
											<option value="1">50</option>
										</select>
									</label>
								</div>
								<ul class="store-grid">
									<li class="active"><i class="fa fa-th"></i></li>
									<!--<li><a href="#"><i class="fa fa-th-list"></i></a></li>-->
								</ul>
							</div>
							<!-- /store top filter -->

							<!-- store products -->
							<div class="row">
							<?php
							$count = 0; // Contador de productos
							foreach ($productostore as $producto):
								// Si el precio viejo no existe, asigna 100 menos que el precio original
								if (empty($producto['old_price'])) {
									$producto['old_price'] = max(0, $producto['price'] - 100); // Asegura que no sea negativo
								}

								// Genera un rating aleatorio entre 4 y 5 si no existe un rating
								$rating = rand(4, 5);
							?>  
							<!-- product -->
							<div class="col-md-4 col-xs-6">
								<div class="product">
									<div class="product-img">
										<img src="assets/images/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['name']); ?>">
										<div class="product-label">
											<?php if ($producto['discount'] > 0): ?>
												<span class="sale">-<?php echo htmlspecialchars($producto['discount']); ?>%</span>
											<?php endif; ?>
											<?php if ($producto['new']): ?>
												<span class="new">Nuevo!</span>
											<?php endif; ?>
										</div>
									</div>
									<div class="product-body">
										<p class="product-category"><?php echo htmlspecialchars($producto['category_name']); ?></p>
										<h3 class="product-name"><a href="product_detalle.php?id=<?php echo $producto['id']; ?>"><?php echo htmlspecialchars($producto['name']); ?></a></h3>
										<h4 class="product-price">$<?php echo number_format($producto['price'], 2); ?>
											<del class="product-old-price">$<?php echo number_format($producto['old_price'], 2); ?></del>
										</h4>
										<div class="product-rating" style="display: none;">
											<?php for ($i = 0; $i < $rating; $i++): ?>
												<i class="fa fa-star"></i>
											<?php endfor; ?>
										</div>
										<div class="product-btns">
											<button class="add-to-wishlist" data-product-id="<?php echo $producto['id']; ?>"><i class="fa fa-heart-o"></i><span class="tooltipp">Añadir carrito</span></button>
											<!--<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>-->
										</div>
									</div>
									<div class="add-to-cart">
										<button class="add-to-cart-btn"  data-product-id="<?php echo $producto['id']; ?>"><i class="fa fa-shopping-cart"></i> add to cart</button>
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


						<!-- store bottom filter -->
						<div class="store-filter clearfix">
							<span class="store-qty">Showing 20-100 products</span>
							<ul class="store-pagination">
								<li class="active">1</li>
								<li><a href="#">2</a></li>
								<li><a href="#">3</a></li>
								<li><a href="#">4</a></li>
								<li><a href="#"><i class="fa fa-angle-right"></i></a></li>
							</ul>
						</div>
						<!-- /store bottom filter -->
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