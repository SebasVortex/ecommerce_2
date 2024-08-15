<?php
include('consultas/producto.php'); // Incluye el archivo que recupera los datos de los productos
?>
<?php include 'assets/includes/head.php';?>
</head>
	<body>
		<!-- HEADER -->
		<?php include 'assets/includes/header.php';?>
		<!-- HEADER -->

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
							<img src="assets/images/A2_SOLUCIONES.png" alt="">
							</div>
							<div class="shop-body">
								<h3>Sistemas<br>hibridos</h3>
								<a href="#" class="cta-btn">Ver mas <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					<!-- /shop -->

					<!-- shop -->
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<div class="shop-img">
								<img src="assets/images/A2_PRODUCTOS.png" alt="">
							</div>
							<div class="shop-body">
								<h3>Baterias</h3>
								<a href="#" class="cta-btn">Ver mas <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					<!-- /shop -->

					<!-- shop -->
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<div class="shop-img">
								<img src="assets/images/A2_SOLUCIONES.png" alt="">
							</div>
							<div class="shop-body">
								<h3>Plantas<br>Modulares</h3>
								<a href="#" class="cta-btn">Ver mas <i class="fa fa-arrow-circle-right"></i></a>
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
							<h3 class="title">New Products</h3>
							<div class="section-nav">
								<ul class="section-tab-nav tab-nav">
									<li class="active"><a data-toggle="tab" href="#tab1">Laptops</a></li>
									<li><a data-toggle="tab" href="#tab1">Smartphones</a></li>
									<li><a data-toggle="tab" href="#tab1">Cameras</a></li>
									<li><a data-toggle="tab" href="#tab1">Accessories</a></li>
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

												</div>
												</a>
											</div>
											<div class="product-body">
												<p class="product-category"><?php echo htmlspecialchars($producto['category_name']); ?></p>
												<h3 class="product-name">
													<a href="product_detalle.php?id=<?php echo $producto['id']; ?>">
														<?php echo htmlspecialchars($producto['name']); ?>
													</a>
												</h3>
												<h4 class="product-price">
													$<?php echo number_format($producto['price'], 2); ?>
												</h4>

												<div class="product-rating">

												</div>

												<div class="product-btns">
													<button class="add-to-wishlist"data-product-id="<?php echo $producto['id']; ?>"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
													<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
												</div>
											</div>
										<div class="add-to-cart">
											<button class="add-to-cart-btn" data-product-id="<?php echo $producto['id']; ?>">
												<i class="fa fa-shopping-cart"></i> add to cart
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
										<h3>02</h3>
										<span>Days</span>
									</div>
								</li>
								<li>
									<div>
										<h3>10</h3>
										<span>Hours</span>
									</div>
								</li>
								<li>
									<div>
										<h3>34</h3>
										<span>Mins</span>
									</div>
								</li>
								<li>
									<div>
										<h3>60</h3>
										<span>Secs</span>
									</div>
								</li>
							</ul>
							<h2 class="text-uppercase">hot deal this week</h2>
							<p>New Collection Up to 50% OFF</p>
							<a class="primary-btn cta-btn" href="#">Shop now</a>
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
							<h3 class="title">Top selling</h3>
							<div class="section-nav">
								<ul class="section-tab-nav tab-nav">
									<li class="active"><a data-toggle="tab" href="#tab2">Laptops</a></li>
									<li><a data-toggle="tab" href="#tab2">Smartphones</a></li>
									<li><a data-toggle="tab" href="#tab2">Cameras</a></li>
									<li><a data-toggle="tab" href="#tab2">Accessories</a></li>
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

												</div>
											</div>
											<div class="product-body">
												<p class="product-category"><?php echo htmlspecialchars($producto['category_name']); ?></p>
												<h3 class="product-name">
													<a href="product_detalle.php?id=<?php echo $producto['id']; ?>">
														<?php echo htmlspecialchars($producto['name']); ?>
													</a>
												</h3>
												<h4 class="product-price">
													$<?php echo number_format($producto['price'], 2); ?>
												</h4>

												<div class="product-rating">

												</div>

												<div class="product-btns">
													<button class="add-to-wishlist"data-product-id="<?php echo $producto['id']; ?>"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
													<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
												</div>
											</div>
											<div class="add-to-cart">
												<button class="add-to-cart-btn" data-product-id="<?php echo $producto['id']; ?>"><i class="fa fa-shopping-cart"></i> add to cart</button>
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
							<h4 class="title">Top selling</h4>
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
											<h3 class="product-name"><a href="#"><?php echo htmlspecialchars($producto['name']); ?></a></h3>
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
							<h4 class="title">Top selling</h4>
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
											<h3 class="product-name"><a href="#"><?php echo htmlspecialchars($producto['name']); ?></a></h3>
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
							<h4 class="title">Top selling</h4>
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
                    <h3 class="product-name"><a href="#"><?php echo htmlspecialchars($producto['name']); ?></a></h3>
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
		<!-- /PIE DE PÁGINA -->
	</body>
</html>