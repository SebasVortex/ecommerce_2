<!-- HEADER -->
<header>
			<!-- TOP HEADER -->
			<div id="top-header">
				<div class="container">
					<ul class="header-links pull-left">
						<li><a href="#"><i class="fa fa-phone"></i> (+54)11 4488 4489</a></li>
						<li><a href="#"><i class="fa fa-envelope-o"></i> ventas@sistemasenergeticos.com.ar</a></li>
						<li><a href="#"><i class="fa fa-map-marker"></i> Av. Díaz Vélez 1240 (C.P. 1702)
						Ciudadela, Buenos Aires, Argentina</a></li>
					</ul>
					<ul class="header-links pull-right">
						<li><a href="#"><i class="fa fa-user-o"></i> Mi cuenta</a></li>
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
								<form>
									<select class="input-select">
										<option value="0">Categorias</option>
										<option value="1">Category 01</option>
										<option value="1">Category 02</option>
									</select>
									<input class="input" placeholder="Buscar productos">
									<button class="search-btn">Buscar</button>
								</form>
							</div>
						</div>
						<!-- /SEARCH BAR -->

						<!-- ACCOUNT -->
						<div class="col-md-3 clearfix">
							<div class="header-ctn">
								<!-- Wishlist -->
								<div>
									<a href="#">
										<i class="fa fa-heart-o"></i>
										<span>Favoritos</span>
										<div class="qty">2</div>
									</a>
								</div>
								<!-- /Wishlist -->

									<!-- HTML para mostrar el carrito -->
									<div class="cart-dropdown">
										<div class="cart-list">
											<?php if (!empty($carrito)): ?>
												<?php foreach ($carrito as $item): ?>
													<div class="product-widget">
														<div class="product-img">
															<img src="assets/images/<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
														</div>
														<div class="product-body">
															<h3 class="product-name"><a href="#"><?php echo htmlspecialchars($item['name']); ?></a></h3>
															<h4 class="product-price"><span class="qty"><?php echo $item['quantity']; ?>x</span>$<?php echo number_format($item['price'], 2); ?></h4>
														</div>
														<form method="POST" action="eliminar_producto.php">
															<input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
															<button class="delete"><i class="fa fa-close"></i></button>
														</form>
													</div>
												<?php endforeach; ?>
											<?php else: ?>
												<p>No hay productos en el carrito.</p>
											<?php endif; ?>
										</div>
										<div class="cart-summary">
											<small><?php echo $totalCantidad; ?> Item(s) seleccionados</small>
											<h5>SUBTOTAL: $<?php echo number_format($totalPrecio, 2); ?></h5>
										</div>
										<div class="cart-btns">
											<a href="ver_carrito.php">Ver carrito</a>
											<a href="finalizar_compra.php">Finalizar <i class="fa fa-arrow-circle-right"></i></a>
										</div>
									</div>


								<!-- Menu Toogle -->
								<div class="menu-toggle">
									<a href="#">
										<i class="fa fa-bars"></i>
										<span>Menu</span>
									</a>
								</div>
								<!-- /Menu Toogle -->
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