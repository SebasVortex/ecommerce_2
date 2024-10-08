
<?php include 'config/productdetalle.php';?>
<?php include 'assets/includes/head.php';?>
<title><?php echo htmlspecialchars($product['category_name']); ?> - <?php echo htmlspecialchars($product['name']); ?></title>
</head>
	<body>
		<!-- HEADER -->
		<?php include 'assets/includes/header.php';?>

		<!-- MIGAS DE PAN -->
		<div id="breadcrumb" class="section">
			<!-- contenedor -->
			<div class="container">
				<!-- fila -->
				<div class="row">
					<div class="col-md-12">
						<ul class="breadcrumb-tree">
							<li><a href="index.php">Inicio</a></li>
							<li><a href="store.php">Todas las Categorías</a></li>
							<li><a href="store.php?category%5B%5D=<?php echo htmlspecialchars($product['category_id']); ?>"><?php echo htmlspecialchars($product['category_name']); ?></a></li>
							<li class="active"><?php echo htmlspecialchars($product['brand_name']); ?> - <?php echo htmlspecialchars($product['name']); ?></li>
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
							<?php if (!empty($imagenes)): ?>
								<?php foreach ($imagenes as $imagen): ?>
									<div class="product-preview">
										<img src="assets/images/<?php echo htmlspecialchars($imagen['imagen']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
									</div>
								<?php endforeach; ?>
							<?php else: ?>
								<div class="product-preview">
									<img src="assets/images/<?php echo htmlspecialchars($product['imagen']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
								</div>
							<?php endif; ?>
						</div>
					</div>

					<!-- /Imagen principal del producto -->

					<!-- Imágenes en miniatura del producto -->
					<div class="col-md-2 col-md-pull-5">
						<div id="product-imgs">
							<?php if (!empty($imagenes)): ?>
								<?php foreach ($imagenes as $imagen): ?>
									<div class="product-preview">
										<img src="assets/images/<?php echo htmlspecialchars($imagen['imagen']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
									</div>
								<?php endforeach; ?>
							<?php else: ?>
								<div class="product-preview">
									<img src="assets/images/<?php echo htmlspecialchars($product['imagen']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
								</div>
							<?php endif; ?>
						</div>
					</div>
					<!-- /Imágenes en miniatura del producto -->

					<!-- Detalles del producto -->
					<div class="col-md-5">
						<div class="product-details">
							<h2 class="product-name"><?php echo htmlspecialchars($product['brand_name']); ?> - <?php echo htmlspecialchars($product['name']); ?></h2>
							<!--
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
							-->
							<div>
								<h3 class="product-price"><?php echo number_format($product['price'], 2); ?><!--<del class="product-old-price">$990.00</del>--></h3>
								<span class="product-available"><?php echo htmlspecialchars($product['stock']); ?></span>
							</div>
							<p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

							

							<div class="add-to-cart">
								<div class="qty-label">
									Cantidad
									<div class="input-number">
										<input type="number" id="quantity-<?php echo htmlspecialchars($product['id']); ?>" value="1" min="1">
										<span class="qty-up">+</span>
										<span class="qty-down">-</span>
									</div>
								</div>
								<button class="add-to-cart-btn" onclick="addToCart(<?php echo htmlspecialchars($product['id']); ?>)"><i class="fa fa-shopping-cart"></i> Añadir al carrito</button>
							</div>

							<ul class="product-btns">
							<!--<li><a href="#"><i class="fa fa-heart-o"></i> añadir a la lista de deseos</a></li>-->
							</ul>

							<ul class="product-links">
								<li>Categoría:</li>
								<li><a href="store.php?category%5B%5D=<?php echo htmlspecialchars($product['category_id']); ?>"><?php echo htmlspecialchars($product['category_name']); ?></a></li>
							</ul>

							<ul class="product-links">
								<li>Compartir:</li>
								<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
								<li><a href="#"><i class="fa fa-instagram"></i></a></li>
								<li><a href="#"><i class="fa fa-youtube"></i></a></li>
							</ul>

						</div>
					</div>
					<!-- /Detalles del producto -->

					<!-- Pestaña del producto -->
					<div class="col-md-12">
						<div id="product-tab">
							<!-- navegación de pestañas del producto -->
							<ul class="tab-nav">
								<li class="active bg-cl"><a data-toggle="tab" href="#tab1">Especificaciones</a></li>
								<li class="bg-cl"><a data-toggle="tab" href="#tab2">Soporte</a></li>
								<!-- <li><a data-toggle="tab" href="#tab3">Opiniones (3)</a></li> -->
							</ul>
							<!-- /navegación de pestañas del producto -->

							<!-- contenido de la pestaña del producto -->
							<div class="tab-content">
								<!-- tab1  -->
								<div id="tab1" class="tab-pane fade in active">
									<div class="row">
										<div class="col-md-12">
									            <table class="table table-striped">
													<thead>
														<tr>
															<th></th>
															<th></th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($characteristics as $characteristic): ?>
															<tr>
																<td><?php echo htmlspecialchars($characteristic['name']); ?></td>
																<td><?php echo htmlspecialchars($characteristic['value']); ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
										</div>
									</div>
								</div>
								<!-- /tab1  -->

								<!-- tab2  -->
								<div id="tab2" class="tab-pane fade in">
									<div class="row">
										<div class="col-md-12" style="display: flex; justify-content: center; gap: 25px;">
											<a href="<?php echo htmlspecialchars($product['manual']); ?>" target="_blank" class="primary-btn soporte">Datasheet</a>
											<a href="<?php echo htmlspecialchars($product['datasheet']); ?>"  target="_blank" class="primary-btn soporte">Manual</a>
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

		<!-- Sección -->
<div class="section">
    <!-- contenedor -->
    <div class="container">
        <!-- fila -->
        <div class="row">

            <div class="col-md-12">
                <div class="section-title text-center">
                    <h3 class="title">Productos Recomendados</h3>
                </div>
            </div>
			<?php foreach ($relatedProducts as $relatedProduct): ?>
    <!-- producto -->
    <div class="col-md-3 col-xs-6">
        <div class="product">
            <!-- Enlace a la página de detalles del producto -->
			<a class="product-img" href="product_detalle.php?id=<?php echo htmlspecialchars($relatedProduct['id']); ?>">
				<div class="product-img">
					<img src="assets/images/<?php echo htmlspecialchars($relatedProduct['imagen']); ?>" alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>">
					<div class="product-label">
						<?php if ($relatedProduct['discount'] > 0): ?>
							<span class="sale">-<?php echo htmlspecialchars($relatedProduct['discount']); ?>%</span>
						<?php endif; ?>
						<?php if ($relatedProduct['new']): ?>
							<span class="new">Nuevo <i class="fa-solid fa-exclamation fa-shake" style="--fa-animation-duration: 2s;"></i></span>
						<?php endif; ?>
					</div>
				</div>
			</a>
            <div class="product-body">
                <p class="product-category"><?php echo htmlspecialchars($relatedProduct['category_name']); ?></p>
                <h3 class="product-name"><a href="product_detalle.php?id=<?php echo htmlspecialchars($relatedProduct['id']); ?>"><?php echo htmlspecialchars($relatedProduct['brand_name']); ?> - <?php echo htmlspecialchars($relatedProduct['name']); ?></a></h3>
                <h4 class="product-price">$<?php echo number_format($relatedProduct['price'], 2); ?>
                    <?php if (isset($relatedProduct['old_price'])): ?>
                        <del class="product-old-price">$<?php echo number_format($relatedProduct['old_price'], 2); ?></del>
                    <?php endif; ?>
                </h4>
                <div class="product-rating">
                    <!-- Puedes agregar estrellas de calificación si tienes esos datos -->
                </div>
                <div class="product-btns">
                    <button class="add-to-wishlist" onclick="addToCart(<?php echo htmlspecialchars($relatedProduct['id']); ?>)"><i class="fa-solid fa-cart-plus fa-flip" style="--fa-animation-duration: 3s;"></i><span class="tooltipp">añadir al carrito</span></button>
					<button class="quick-view" data-product-id="<?php echo $relatedProduct['id']; ?>">
														<i class="fa fa-eye"></i><span class="tooltipp">Ver mas</span>
													</button>
                </div>
            </div>
            <div class="add-to-cart">
                <button class="add-to-cart-btn" onclick="addToCart(<?php echo htmlspecialchars($relatedProduct['id']); ?>)"><i class="fa fa-shopping-cart"></i> añadir al carrito</button>
            </div>
        </div>
    </div>
    <!-- /producto -->
<?php endforeach; ?>

            <div class="clearfix visible-sm visible-xs"></div>

        </div>
        <!-- /fila -->
    </div>
    <!-- /contenedor -->
</div>
<!-- /Sección -->
		<!-- PIE DE PÁGINA -->
		<?php include 'assets/includes/footer.php';?>
		<!-- /PIE DE PÁGINA -->
	</body>
</html>