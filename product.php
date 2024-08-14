<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <!-- Las 3 etiquetas meta anteriores *deben* ir primero en el head; cualquier otro contenido en el head debe ir *después* de estas etiquetas -->

		<title>Electro - Plantilla HTML de Comercio Electrónico</title>

 		<!-- Fuente de Google -->
 		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

 		<!-- Bootstrap -->
 		<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css"/>

 		<!-- Slick -->
 		<link type="text/css" rel="stylesheet" href="css/slick.css"/>
 		<link type="text/css" rel="stylesheet" href="css/slick-theme.css"/>

 		<!-- nouislider -->
 		<link type="text/css" rel="stylesheet" href="css/nouislider.min.css"/>

 		<!-- Icono de Font Awesome -->
 		<link rel="stylesheet" href="css/font-awesome.min.css">

 		<!-- Hoja de estilo personalizada -->
 		<link type="text/css" rel="stylesheet" href="css/style.css"/>

		<!-- Compatibilidad con HTML5 y Respond.js para soporte de elementos y consultas de medios en IE8 -->
		<!-- ADVERTENCIA: Respond.js no funciona si ves la página a través de file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

    </head>
	<body>
		<!-- CABECERA -->
		<header>
			<!-- CABECERA SUPERIOR -->
			<div id="top-header">
				<div class="container">
					<ul class="header-links pull-left">
						<li><a href="#"><i class="fa fa-phone"></i> +021-95-51-84</a></li>
						<li><a href="#"><i class="fa fa-envelope-o"></i> email@email.com</a></li>
						<li><a href="#"><i class="fa fa-map-marker"></i> 1734 Stonecoal Road</a></li>
					</ul>
					<ul class="header-links pull-right">
						<li><a href="#"><i class="fa fa-dollar"></i> USD</a></li>
						<li><a href="#"><i class="fa fa-user-o"></i> Mi Cuenta</a></li>
					</ul>
				</div>
			</div>
			<!-- /CABECERA SUPERIOR -->

			<!-- CABECERA PRINCIPAL -->
			<div id="header">
				<!-- contenedor -->
				<div class="container">
					<!-- fila -->
					<div class="row">
						<!-- LOGO -->
						<div class="col-md-3">
							<div class="header-logo">
								<a href="#" class="logo">
									<img src="./img/logo.png" alt="">
								</a>
							</div>
						</div>
						<!-- /LOGO -->

						<!-- BARRA DE BÚSQUEDA -->
						<div class="col-md-6">
							<div class="header-search">
								<form>
									<select class="input-select">
										<option value="0">Todas las Categorías</option>
										<option value="1">Categoría 01</option>
										<option value="1">Categoría 02</option>
									</select>
									<input class="input" placeholder="Buscar aquí">
									<button class="search-btn">Buscar</button>
								</form>
							</div>
						</div>
						<!-- /BARRA DE BÚSQUEDA -->

						<!-- CUENTA -->
						<div class="col-md-3 clearfix">
							<div class="header-ctn">
								<!-- Lista de deseos -->
								<div>
									<a href="#">
										<i class="fa fa-heart-o"></i>
										<span>Tu Lista de Deseos</span>
										<div class="qty">2</div>
									</a>
								</div>
								<!-- /Lista de deseos -->

								<!-- Carrito -->
								<div class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
										<i class="fa fa-shopping-cart"></i>
										<span>Tu Carrito</span>
										<div class="qty">3</div>
									</a>
									<div class="cart-dropdown">
										<div class="cart-list">
											<div class="product-widget">
												<div class="product-img">
													<img src="./img/product01.png" alt="">
												</div>
												<div class="product-body">
													<h3 class="product-name"><a href="#">nombre del producto</a></h3>
													<h4 class="product-price"><span class="qty">1x</span>$980.00</h4>
												</div>
												<button class="delete"><i class="fa fa-close"></i></button>
											</div>

											<div class="product-widget">
												<div class="product-img">
													<img src="./img/product02.png" alt="">
												</div>
												<div class="product-body">
													<h3 class="product-name"><a href="#">nombre del producto</a></h3>
													<h4 class="product-price"><span class="qty">3x</span>$980.00</h4>
												</div>
												<button class="delete"><i class="fa fa-close"></i></button>
											</div>
										</div>
										<div class="cart-summary">
											<small>3 artículo(s) seleccionados</small>
											<h5>SUBTOTAL: $2940.00</h5>
										</div>
										<div class="cart-btns">
											<a href="#">Ver Carrito</a>
											<a href="#">Pagar  <i class="fa fa-arrow-circle-right"></i></a>
										</div>
									</div>
								</div>
								<!-- /Carrito -->

								<!-- Menú Toggle -->
								<div class="menu-toggle">
									<a href="#">
										<i class="fa fa-bars"></i>
										<span>Menú</span>
									</a>
								</div>
								<!-- /Menú Toggle -->
							</div>
						</div>
						<!-- /CUENTA -->
					</div>
					<!-- /fila -->
				</div>
				<!-- /contenedor -->
			</div>
			<!-- /CABECERA PRINCIPAL -->
		</header>
		<!-- /CABECERA -->

		<!-- NAVEGACIÓN -->
		<nav id="navigation">
			<!-- contenedor -->
			<div class="container">
				<!-- navegación responsiva -->
				<div id="responsive-nav">
					<!-- NAVEGACIÓN -->
					<ul class="main-nav nav navbar-nav">
						<li class="active"><a href="#">Inicio</a></li>
						<li><a href="#">Ofertas</a></li>
						<li><a href="#">Categorías</a></li>
						<li><a href="#">Laptops</a></li>
						<li><a href="#">Smartphones</a></li>
						<li><a href="#">Cámaras</a></li>
						<li><a href="#">Accesorios</a></li>
					</ul>
					<!-- /NAVEGACIÓN -->
				</div>
				<!-- /navegación responsiva -->
			</div>
			<!-- /contenedor -->
		</nav>
		<!-- /NAVEGACIÓN -->

		<!-- MIGAS DE PAN -->
		<div id="breadcrumb" class="section">
			<!-- contenedor -->
			<div class="container">
				<!-- fila -->
				<div class="row">
					<div class="col-md-12">
						<ul class="breadcrumb-tree">
							<li><a href="#">Inicio</a></li>
							<li><a href="#">Todas las Categorías</a></li>
							<li><a href="#">Accesorios</a></li>
							<li><a href="#">Auriculares</a></li>
							<li class="active">Nombre del producto</li>
						</ul>
					</div>
				</div>
				<!-- /fila -->
			</div>
			<!-- /contenedor -->
		</div>
		<!-- /MIGAS DE PAN -->

		<!-- SECCIÓN -->
		<div class="section">
			<!-- contenedor -->
			<div class="container">
				<!-- fila -->
				<div class="row">
					<!-- Imagen principal del producto -->
					<div class="col-md-5 col-md-push-2">
						<div id="product-main-img">
							<div class="product-preview">
								<img src="./img/product01.png" alt="">
							</div>

							<div class="product-preview">
								<img src="./img/product03.png" alt="">
							</div>

							<div class="product-preview">
								<img src="./img/product06.png" alt="">
							</div>

							<div class="product-preview">
								<img src="./img/product08.png" alt="">
							</div>
						</div>
					</div>
					<!-- /Imagen principal del producto -->

					<!-- Imágenes en miniatura del producto -->
					<div class="col-md-2  col-md-pull-5">
						<div id="product-imgs">
							<div class="product-preview">
								<img src="./img/product01.png" alt="">
							</div>

							<div class="product-preview">
								<img src="./img/product03.png" alt="">
							</div>

							<div class="product-preview">
								<img src="./img/product06.png" alt="">
							</div>

							<div class="product-preview">
								<img src="./img/product08.png" alt="">
							</div>
						</div>
					</div>
					<!-- /Imágenes en miniatura del producto -->

					<!-- Detalles del producto -->
					<div class="col-md-5">
						<div class="product-details">
							<h2 class="product-name">nombre del producto</h2>
							<div>
								<div class="product-rating">
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star-o"></i>
								</div>
								<a class="review-link" href="#">10 Opinión(es) | Añade tu opinión</a>
							</div>
							<div>
								<h3 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h3>
								<span class="product-available">En Stock</span>
							</div>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>

							<div class="product-options">
								<label>
									Tamaño
									<select class="input-select">
										<option value="0">X</option>
									</select>
								</label>
								<label>
									Color
									<select class="input-select">
										<option value="0">Rojo</option>
									</select>
								</label>
							</div>

							<div class="add-to-cart">
								<div class="qty-label">
									Cantidad
									<div class="input-number">
										<input type="number">
										<span class="qty-up">+</span>
										<span class="qty-down">-</span>
									</div>
								</div>
								<button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> añadir al carrito</button>
							</div>

							<ul class="product-btns">
								<li><a href="#"><i class="fa fa-heart-o"></i> añadir a la lista de deseos</a></li>
								<li><a href="#"><i class="fa fa-exchange"></i> añadir para comparar</a></li>
							</ul>

							<ul class="product-links">
								<li>Categoría:</li>
								<li><a href="#">Auriculares</a></li>
								<li><a href="#">Accesorios</a></li>
							</ul>

							<ul class="product-links">
								<li>Compartir:</li>
								<li><a href="#"><i class="fa fa-facebook"></i></a></li>
								<li><a href="#"><i class="fa fa-twitter"></i></a></li>
								<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
								<li><a href="#"><i class="fa fa-envelope"></i></a></li>
							</ul>

						</div>
					</div>
					<!-- /Detalles del producto -->

					<!-- Pestaña del producto -->
					<div class="col-md-12">
						<div id="product-tab">
							<!-- navegación de pestañas del producto -->
							<ul class="tab-nav">
								<li class="active"><a data-toggle="tab" href="#tab1">Descripción</a></li>
								<li><a data-toggle="tab" href="#tab2">Detalles</a></li>
								<li><a data-toggle="tab" href="#tab3">Opiniones (3)</a></li>
							</ul>
							<!-- /navegación de pestañas del producto -->

							<!-- contenido de la pestaña del producto -->
							<div class="tab-content">
								<!-- tab1  -->
								<div id="tab1" class="tab-pane fade in active">
									<div class="row">
										<div class="col-md-12">
											<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
										</div>
									</div>
								</div>
								<!-- /tab1  -->

								<!-- tab2  -->
								<div id="tab2" class="tab-pane fade in">
									<div class="row">
										<div class="col-md-12">
											<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
										</div>
									</div>
								</div>
								<!-- /tab2  -->

								<!-- tab3  -->
								<div id="tab3" class="tab-pane fade in">
									<div class="row">
										<!-- Calificación -->
										<div class="col-md-3">
											<div id="rating">
												<div class="rating-avg">
													<span>4.5</span>
													<div class="rating-stars">
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star-o"></i>
													</div>
												</div>
												<ul class="rating">
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
														</div>
														<div class="rating-progress">
															<div style="width: 80%;"></div>
														</div>
														<span class="sum">3</span>
													</li>
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="rating-progress">
															<div style="width: 60%;"></div>
														</div>
														<span class="sum">2</span>
													</li>
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="rating-progress">
															<div></div>
														</div>
														<span class="sum">0</span>
													</li>
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="rating-progress">
															<div></div>
														</div>
														<span class="sum">0</span>
													</li>
													<li>
														<div class="rating-stars">
															<i class="fa fa-star"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
															<i class="fa fa-star-o"></i>
														</div>
														<div class="rating-progress">
															<div></div>
														</div>
														<span class="sum">0</span>
													</li>
												</ul>
											</div>
										</div>
										<!-- /Calificación -->

										<!-- Opiniones -->
										<div class="col-md-6">
											<div id="reviews">
												<ul class="reviews">
													<li>
														<div class="review-heading">
															<h5 class="name">Juan</h5>
															<p class="date">27 DIC 2018, 8:00 PM</p>
															<div class="review-rating">
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star-o empty"></i>
															</div>
														</div>
														<div class="review-body">
															<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
														</div>
													</li>
													<li>
														<div class="review-heading">
															<h5 class="name">Juan</h5>
															<p class="date">27 DIC 2018, 8:00 PM</p>
															<div class="review-rating">
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star-o empty"></i>
															</div>
														</div>
														<div class="review-body">
															<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
														</div>
													</li>
													<li>
														<div class="review-heading">
															<h5 class="name">Juan</h5>
															<p class="date">27 DIC 2018, 8:00 PM</p>
															<div class="review-rating">
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star"></i>
																<i class="fa fa-star-o empty"></i>
															</div>
														</div>
														<div class="review-body">
															<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
														</div>
													</li>
												</ul>
												<ul class="reviews-pagination">
													<li class="active">1</li>
													<li><a href="#">2</a></li>
													<li><a href="#">3</a></li>
													<li><a href="#">4</a></li>
													<li><a href="#"><i class="fa fa-angle-right"></i></a></li>
												</ul>
											</div>
										</div>
										<!-- /Opiniones -->

										<!-- Formulario de Opinión -->
										<div class="col-md-3">
											<div id="review-form">
												<form class="review-form">
													<input class="input" type="text" placeholder="Tu Nombre">
													<input class="input" type="email" placeholder="Tu Correo Electrónico">
													<textarea class="input" placeholder="Tu Opinión"></textarea>
													<div class="input-rating">
														<span>Tu Calificación: </span>
														<div class="stars">
															<input id="star5" name="rating" value="5" type="radio"><label for="star5"></label>
															<input id="star4" name="rating" value="4" type="radio"><label for="star4"></label>
															<input id="star3" name="rating" value="3" type="radio"><label for="star3"></label>
															<input id="star2" name="rating" value="2" type="radio"><label for="star2"></label>
															<input id="star1" name="rating" value="1" type="radio"><label for="star1"></label>
														</div>
													</div>
													<button class="primary-btn">Enviar</button>
												</form>
											</div>
										</div>
										<!-- /Formulario de Opinión -->
									</div>
								</div>
								<!-- /tab3  -->
							</div>
							<!-- /contenido de la pestaña del producto  -->
						</div>
					</div>
					<!-- /Pestaña del producto -->
				</div>
				<!-- /fila -->
			</div>
			<!-- /contenedor -->
		</div>
		<!-- /SECCIÓN -->

		<!-- Sección -->
		<div class="section">
			<!-- contenedor -->
			<div class="container">
				<!-- fila -->
				<div class="row">

					<div class="col-md-12">
						<div class="section-title text-center">
							<h3 class="title">Productos Relacionados</h3>
						</div>
					</div>

					<!-- producto -->
					<div class="col-md-3 col-xs-6">
						<div class="product">
							<div class="product-img">
								<img src="./img/product01.png" alt="">
								<div class="product-label">
									<span class="sale">-30%</span>
								</div>
							</div>
							<div class="product-body">
								<p class="product-category">Categoría</p>
								<h3 class="product-name"><a href="#">nombre del producto</a></h3>
								<h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
								<div class="product-rating">
								</div>
								<div class="product-btns">
									<button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">añadir a la lista de deseos</span></button>
									<button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">añadir para comparar</span></button>
									<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">vista rápida</span></button>
								</div>
							</div>
							<div class="add-to-cart">
								<button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> añadir al carrito</button>
							</div>
						</div>
					</div>
					<!-- /producto -->

					<!-- producto -->
					<div class="col-md-3 col-xs-6">
						<div class="product">
							<div class="product-img">
								<img src="./img/product02.png" alt="">
								<div class="product-label">
									<span class="new">NUEVO</span>
								</div>
							</div>
							<div class="product-body">
								<p class="product-category">Categoría</p>
								<h3 class="product-name"><a href="#">nombre del producto</a></h3>
								<h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
								<div class="product-rating">
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
									<i class="fa fa-star"></i>
								</div>
								<div class="product-btns">
									<button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">añadir a la lista de deseos</span></button>
									<button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">añadir para comparar</span></button>
									<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">vista rápida</span></button>
								</div>
							</div>
							<div class="add-to-cart">
								<button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> añadir al carrito</button>
							</div>
						</div>
					</div>
					<!-- /producto -->

					<div class="clearfix visible-sm visible-xs"></div>

					<!-- producto -->
					<div class="col-md-3 col-xs-6">
						<div class="product">
							<div class="product-img">
								<img src="./img/product03.png" alt="">
							</div>
							<div class="product-body">
								<p class="product-category">Categoría</p>
								<h3 class="product-name"><a href="#">nombre del producto</a></h3>
								<h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
								<div class="product-rating">
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star-o"></i>
								</div>
								<div class="product-btns">
									<button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">añadir a la lista de deseos</span></button>
									<button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">añadir para comparar</span></button>
									<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">vista rápida</span></button>
								</div>
							</div>
							<div class="add-to-cart">
								<button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> añadir al carrito</button>
							</div>
						</div>
					</div>
					<!-- /producto -->

					<!-- producto -->
					<div class="col-md-3 col-xs-6">
						<div class="product">
							<div class="product-img">
								<img src="./img/product04.png" alt="">
							</div>
							<div class="product-body">
								<p class="product-category">Categoría</p>
								<h3 class="product-name"><a href="#">nombre del producto</a></h3>
								<h4 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h4>
								<div class="product-rating">
								</div>
								<div class="product-btns">
									<button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">añadir a la lista de deseos</span></button>
									<button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">añadir para comparar</span></button>
									<button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">vista rápida</span></button>
								</div>
							</div>
							<div class="add-to-cart">
								<button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> añadir al carrito</button>
							</div>
						</div>
					</div>
					<!-- /producto -->

				</div>
				<!-- /fila -->
			</div>
			<!-- /contenedor -->
		</div>
		<!-- /Sección -->

		<!-- BOLETÍN -->
		<div id="newsletter" class="section">
			<!-- contenedor -->
			<div class="container">
				<!-- fila -->
				<div class="row">
					<div class="col-md-12">
						<div class="newsletter">
							<p>Regístrate para el <strong>BOLETÍN</strong></p>
							<form>
								<input class="input" type="email" placeholder="Ingresa tu Correo Electrónico">
								<button class="newsletter-btn"><i class="fa fa-envelope"></i> Suscribirse</button>
							</form>
							<ul class="newsletter-follow">
								<li>
									<a href="#"><i class="fa fa-facebook"></i></a>
								</li>
								<li>
									<a href="#"><i class="fa fa-twitter"></i></a>
								</li>
								<li>
									<a href="#"><i class="fa fa-instagram"></i></a>
								</li>
								<li>
									<a href="#"><i class="fa fa-pinterest"></i></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- /fila -->
			</div>
			<!-- /contenedor -->
		</div>
		<!-- /BOLETÍN -->

		<!-- PIE DE PÁGINA -->
		<footer id="footer">
			<!-- pie de página superior -->
			<div class="section">
				<!-- contenedor -->
				<div class="container">
					<!-- fila -->
					<div class="row">
						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Sobre Nosotros</h3>
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut.</p>
								<ul class="footer-links">
									<li><a href="#"><i class="fa fa-map-marker"></i>1734 Stonecoal Road</a></li>
									<li><a href="#"><i class="fa fa-phone"></i>+021-95-51-84</a></li>
									<li><a href="#"><i class="fa fa-envelope-o"></i>email@email.com</a></li>
								</ul>
							</div>
						</div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Categorías</h3>
								<ul class="footer-links">
									<li><a href="#">Ofertas</a></li>
									<li><a href="#">Laptops</a></li>
									<li><a href="#">Smartphones</a></li>
									<li><a href="#">Cámaras</a></li>
									<li><a href="#">Accesorios</a></li>
								</ul>
							</div>
						</div>

						<div class="clearfix visible-xs"></div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Información</h3>
								<ul class="footer-links">
									<li><a href="#">Sobre Nosotros</a></li>
									<li><a href="#">Contáctanos</a></li>
									<li><a href="#">Política de Privacidad</a></li>
									<li><a href="#">Pedidos y Devoluciones</a></li>
									<li><a href="#">Términos y Condiciones</a></li>
								</ul>
							</div>
						</div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Servicio</h3>
								<ul class="footer-links">
									<li><a href="#">Mi Cuenta</a></li>
									<li><a href="#">Ver Carrito</a></li>
									<li><a href="#">Lista de Deseos</a></li>
									<li><a href="#">Rastrear mi Pedido</a></li>
									<li><a href="#">Ayuda</a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /fila -->
				</div>
				<!-- /contenedor -->
			</div>
			<!-- /pie de página superior -->

			<!-- pie de página inferior -->
			<div id="bottom-footer" class="section">
				<div class="container">
					<!-- fila -->
					<div class="row">
						<div class="col-md-12 text-center">
							<ul class="footer-payments">
								<li><a href="#"><i class="fa fa-cc-visa"></i></a></li>
								<li><a href="#"><i class="fa fa-credit-card"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-paypal"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-mastercard"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-discover"></i></a></li>
								<li><a href="#"><i class="fa fa-cc-amex"></i></a></li>
							</ul>
							<span class="copyright">
								<!-- El enlace de vuelta a Colorlib no se puede eliminar. Esta plantilla está licenciada bajo CC BY 3.0. -->
								Copyright &copy;<script>document.write(new Date().getFullYear());</script> Todos los derechos reservados | Esta plantilla está hecha con <i class="fa fa-heart-o" aria-hidden="true"></i> por <a href="https://colorlib.com" target="_blank">Colorlib</a>
							<!-- El enlace de vuelta a Colorlib no se puede eliminar. Esta plantilla está licenciada bajo CC BY 3.0. -->
							</span>
						</div>
					</div>
						<!-- /fila -->
				</div>
				<!-- /contenedor -->
			</div>
			<!-- /pie de página inferior -->
		</footer>
		<!-- /PIE DE PÁGINA -->

		<!-- Plugins de jQuery -->
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/slick.min.js"></script>
		<script src="js/nouislider.min.js"></script>
		<script src="js/jquery.zoom.min.js"></script>
		<script src="js/main.js"></script>

	</body>
</html>
