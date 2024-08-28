<?php
include('config/producto.php'); // Incluye el archivo que recupera los datos de los productos
// Obtener la oferta activa desde la base de datos

$query = "SELECT fecha_inicio, fecha_fin FROM ofertas WHERE index_id = 1"; // Ajusta la consulta según sea necesario
$stmt = $conn->prepare($query);

// Verificar si la consulta se ejecutó correctamente
if ($stmt->execute()) {
    // Verificar si se obtuvieron resultados
    $oferta = $stmt->fetch(PDO::FETCH_ASSOC);

    // Comprobar si $oferta no es falso
    if ($oferta !== false) {
        $fechaInicio = $oferta['fecha_inicio'];
        $fechaFin = $oferta['fecha_fin'];
    } else {
        // Manejo en caso de que no se encuentren resultados
        echo "<script>alert('No se encontró ninguna oferta activa.');</script>";
        // Definir valores predeterminados o manejar el error según sea necesario
        $fechaInicio = null;
        $fechaFin = null;
    }
} else {
    // Manejo en caso de que la consulta falle
    echo "<script>alert('Error al ejecutar la consulta.');</script>";
    $fechaInicio = null;
    $fechaFin = null;
}
?>

<?php include 'assets/includes/head.php';?>
</head>
	<body>
		<!-- HEADER -->
		<?php include 'assets/includes/header.php';?>
		<!-- HEADER -->
		 

		<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<!-- shop -->
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<div class="shop-img">
							<img src="assets/images/SOLAR TOWER.png" alt="">
							</div>
							<div class="shop-body">
								<h3>Sistemas<br>hibridos</h3>
								<a href="store.php?category%5B%5D=1" class="cta-btn">Ver mas <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					<!-- /shop -->

					<!-- shop -->
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<div class="shop-img">
								<img src="assets/images/EFB 2200.png" alt="">
							</div>
							<div class="shop-body">
								<h3>Baterías<br>de litio</h3>
								<a href="store.php?category%5B%5D=9" class="cta-btn">Ver mas <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					<!-- /shop -->

					<!-- shop -->
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<div class="shop-img">
								<img src="assets/images/EFP-10U-48R18-S30.png" alt="">
							</div>
							<div class="shop-body">
								<h3>Plantas<br>Modulares</h3>
								<a href="store.php?category%5B%5D=6" class="cta-btn">Ver mas <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					<!-- /shop -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

		<!-- SECTION -->
		<div class="section" >
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">

					<!-- section title -->
					<div class="col-md-12">
						<div class="section-title">
							<h3 class="title">Productos</h3>
							<div class="section-nav">
								<ul class="section-tab-nav tab-nav">
								<li class="active"><a href="store.php">Inversores</a></li>
									<li><a href="store.php?category%5B%5D=11">Paneles Solares</a></li>
									<li><a href="store.php?category%5B%5D=9">Baterías</a></li>
									<li><a href="store.php?category%5B%5D=6">Plantas Modulares</a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /section title -->

					<!-- Products tab & slick -->
					<div class="col-md-12">
						<div class="row">
							<div class="products-tabs">
								<!-- tab -->
								<div id="tab1" class="tab-pane active">
									<div class="products-slick" data-nav="#slick-nav-1">
									<?php foreach ($productos as $producto): ?>
										<div class="product">
										<a class="product-img" href="product_detalle.php?id=<?php echo $producto['id']; ?>">
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
											</a>
											<div class="product-body">
												<p class="product-category"><?php echo htmlspecialchars($producto['category_name']); ?></p>
												<h3 class="product-name">
													<a href="product_detalle.php?id=<?php echo $producto['id']; ?>">
													<?php echo htmlspecialchars($producto['brand_name']); ?> -
														<?php echo htmlspecialchars($producto['name']); ?>
													</a>
												</h3>
												<h4 class="product-price">
													$<?php echo number_format($producto['price'], 2); ?>
												</h4>

												<div class="product-rating">

												</div>

												<div class="product-btns">
													<button class="add-to-wishlist"data-product-id="<?php echo $producto['id']; ?>"><i class="fa fa-heart-o"></i><span class="tooltipp">Añadir al carrito</span></button>
													<button class="quick-view" data-product-id="<?php echo $producto['id']; ?>">
														<i class="fa fa-eye"></i><span class="tooltipp">Ver mas</span>
													</button>
												</div>
											</div>
											<div class="add-to-cart">
											<button class="add-to-cart-btn" data-product-id="<?php echo htmlspecialchars($producto['id']); ?>">
												<i class="fa fa-shopping-cart"></i>Añadir al carrito
											</button>
										</div>
									</div>
										<?php endforeach; ?>

									</div>
									<div id="slick-nav-1" class="products-slick-nav"></div>
								</div>
								<!-- /tab -->
							</div>
						</div>
					</div>
					<!-- Products tab & slick -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

		<!-- HOT DEAL SECTION -->
		<div id="hot-deal" class="section" >
			<!-- container -->
			<div class="container">
					<!-- row -->
					<div class="row">
						<div class="col-md-12">
							<div class="hot-deal">
								<ul class="hot-deal-countdown">
									<li>
										<div>
											<h3 id="days">00</h3>
											<span>Días</span>
										</div>
									</li>
									<li>
										<div>
											<h3 id="hours">00</h3>
											<span>Horas</span>
										</div>
									</li>
									<li>
										<div>
											<h3 id="mins">00</h3>
											<span>Minutos</span>
										</div>
									</li>
									<li>
										<div>
											<h3 id="secs">00</h3>
											<span>Segundos</span>
										</div>
									</li>
								</ul>
								<h2 class="text-uppercase">Descuentazos esta semana!</h2>
								<p>Nuevos Inversores 25% OFF</p>
								<a class="primary-btn cta-btn" href="store.php?category%5B%5D=5">Comprar ahora</a>
							</div>
						</div>
					</div>
					<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /HOT DEAL SECTION -->

		<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">

					<!-- section title -->
					<div class="col-md-12">
						<div class="section-title">
							<h3 class="title">Mas productos</h3>
							<div class="section-nav">
								<ul class="section-tab-nav tab-nav">
									<li class="active"><a href="store.php">Inversores</a></li>
									<li><a href="store.php?category%5B%5D=11">Paneles Solares</a></li>
									<li><a href="store.php?category%5B%5D=9">Baterías</a></li>
									<li><a href="store.php?category%5B%5D=6">Plantas Modulares</a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /section title -->

					<!-- Products tab & slick -->
					<div class="col-md-12">
						<div class="row">
							<div class="products-tabs">
								<!-- tab -->
								<div id="tab2" class="tab-pane fade in active">
									<div class="products-slick" data-nav="#slick-nav-2">
									<?php foreach ($productos as $producto): ?>
										<div class="product">
											<div class="product-img">
											<a class="product-img" href="product_detalle.php?id=<?php echo $producto['id']; ?>">
												<img src="assets/images/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['name']); ?>">
											</a>
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
												<h3 class="product-name">
													<a href="product_detalle.php?id=<?php echo $producto['id']; ?>">
													<?php echo htmlspecialchars($producto['brand_name']); ?> -
														<?php echo htmlspecialchars($producto['name']); ?>
													</a>
												</h3>
												<h4 class="product-price">
													$<?php echo number_format($producto['price'], 2); ?>
												</h4>

												<div class="product-rating">

												</div>

												<div class="product-btns">
													<button class="add-to-wishlist"data-product-id="<?php echo $producto['id']; ?>"><i class="fa fa-heart-o"></i><span class="tooltipp">añadir al carrito</span></button>
													<button class="quick-view" data-product-id="<?php echo $producto['id']; ?>">
														<i class="fa fa-eye"></i><span class="tooltipp">Ver mas</span>
													</button>
												</div>
											</div>
											<div class="add-to-cart">
										<button class="add-to-cart-btn" data-product-id="<?php echo $producto['id']; ?>">
											<i class="fa fa-shopping-cart"></i> Añadir al carrito
										</button>
									</div>
										</div>
										<?php endforeach; ?>


									</div>
									<div id="slick-nav-2" class="products-slick-nav"></div>
								</div>
								<!-- /tab -->
							</div>
						</div>
					</div>
					<!-- /Products tab & slick -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

		<!-- SECTION -->
		<div class="section" style="padding-bottom: 0px;">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-4 col-xs-6">
						<div class="section-title">
							<h4 class="title">Inversores</h4>
							<div class="section-nav">
								<div id="slick-nav-3" class="products-slick-nav"></div>
							</div>
						</div>

						<div class="products-widget-slick" data-nav="#slick-nav-3">
							<div>
								<!-- Mostrar productos de inversores -->
								<?php foreach ($productos_inversores as $producto): ?>
									<!-- product widget -->
									<div class="product-widget">
										<div class="product-img">
											<img src="assets/images/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['name']); ?>" class="img-fluid">
										</div>
										<div class="product-body">
											<p class="product-category"><?php echo htmlspecialchars($producto['category_name']); ?></p>
											<h3 class="product-name"><a href="product_detalle.php?id=<?php echo $producto['id']; ?>"><?php echo htmlspecialchars($producto['brand_name']); ?> - <?php echo htmlspecialchars($producto['name']); ?></a></h3>
											<h4 class="product-price">
												$<?php echo number_format($producto['price'], 2); ?> 
												<?php if ($producto['price'] > 990.00): ?>
													<del class="product-old-price">$990.00</del>
												<?php endif; ?>
											</h4>
										</div>
									</div>
									<!-- /product widget -->
								<?php endforeach; ?>
							</div>
						</div>
					</div>

					<div class="col-md-4 col-xs-6">
						<div class="section-title">
							<h4 class="title">Baterías</h4>
							<div class="section-nav">
								<div id="slick-nav-4" class="products-slick-nav"></div>
							</div>
						</div>
						<div class="products-widget-slick" data-nav="#slick-nav-3">
                                                                            <div>
								<!-- Mostrar productos de baterías -->
								<?php foreach ($productos_baterias as $producto): ?>
									<!-- product widget -->
									<div class="product-widget">
										<div class="product-img">
											<img src="assets/images/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['name']); ?>" class="img-fluid">
										</div>
										<div class="product-body">
											<p class="product-category"><?php echo htmlspecialchars($producto['category_name']); ?></p>
											<h3 class="product-name"><a href="product_detalle.php?id=<?php echo $producto['id']; ?>"> <?php echo htmlspecialchars($producto['brand_name']); ?> - <?php echo htmlspecialchars($producto['name']); ?></a></h3>
											<h4 class="product-price">
												$<?php echo number_format($producto['price'], 2); ?> 
												<?php if ($producto['price'] > 990.00): ?>
													<del class="product-old-price">$990.00</del>
												<?php endif; ?>
											</h4>
										</div>
									</div>
									<!-- /product widget -->
								<?php endforeach; ?>
							</div>
						</div>
					</div>

					<div class="clearfix visible-sm visible-xs"></div>

					<div class="col-md-4 col-xs-6">
						<div class="section-title">
							<h4 class="title">Sistemas hibridos</h4>
							<div class="section-nav">
								<div id="slick-nav-5" class="products-slick-nav"></div>
							</div>
						</div>

						<div class="products-widget-slick" data-nav="#slick-nav-3">
    <div>
        <!-- Mostrar productos de sistemas hibridos -->
        <?php foreach ($productos_sistemas_hibridos as $producto): ?>
            <!-- product widget -->
            <div class="product-widget">
                <div class="product-img">
                    <img src="assets/images/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['name']); ?>" class="img-fluid">
                </div>
                <div class="product-body">
                    <p class="product-category"><?php echo htmlspecialchars($producto['category_name']); ?></p>
                    <h3 class="product-name"><a href="product_detalle.php?id=<?php echo $producto['id']; ?>"> <?php echo htmlspecialchars($producto['brand_name']); ?> - <?php echo htmlspecialchars($producto['name']); ?></a></h3>
                    <h4 class="product-price">
                        $<?php echo number_format($producto['price'], 2); ?> 
                        <?php if ($producto['price'] > 990.00): ?>
                            <del class="product-old-price">$990.00</del>
                        <?php endif; ?>
                    </h4>
                </div>
            </div>
            <!-- /product widget -->
        <?php endforeach; ?>
    </div>
</div>

				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

		<!-- PIE DE PÁGINA -->
		<?php include 'assets/includes/footer.php';?>
					<!-- Temporizador index principal -->
<script>
// Verificar si $fechaFin tiene un valor válido antes de pasar a JavaScript
<?php if ($fechaFin): ?>
    var fechaFin = new Date("<?php echo $fechaFin; ?>").getTime();
<?php else: ?>
    var fechaFin = new Date().getTime(); // Usar la fecha actual si no hay oferta
<?php endif; ?>

// Actualizar el contador cada segundo
var x = setInterval(function() {
    var ahora = new Date().getTime();
    var distancia = fechaFin - ahora;

    var dias = Math.floor(distancia / (1000 * 60 * 60 * 24));
    var horas = Math.floor((distancia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutos = Math.floor((distancia % (1000 * 60 * 60)) / (1000 * 60));
    var segundos = Math.floor((distancia % (1000 * 60)) / 1000);

    document.getElementById("days").innerHTML = dias;
    document.getElementById("hours").innerHTML = horas;
    document.getElementById("mins").innerHTML = minutos;
    document.getElementById("secs").innerHTML = segundos;

    // Si el contador llega a cero, mostrar un mensaje
    if (distancia < 0) {
        clearInterval(x);
        document.querySelector(".hot-deal").innerHTML = "<h2 class='text-uppercase'>¡La oferta ha terminado!</h2>";
    }
}, 1000);
</script>

		<!-- /PIE DE PÁGINA -->
	</body>
</html>