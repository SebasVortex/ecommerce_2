<?php
// Consultar todas las categorías
$stmt = $conn->prepare("SELECT name FROM categorias"); // Prepara una consulta SQL para seleccionar todas las categorías
$stmt->execute(); // Ejecuta la consulta
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtiene todos los resultados en un array asociativo
?>

<!-- NEWSLETTER -->
<div id="newsletter" class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<div class="newsletter">
							<p>Sign Up for the <strong>NEWSLETTER</strong></p>
							<form>
								<input class="input" type="email" placeholder="Enter Your Email">
								<button class="newsletter-btn"><i class="fa fa-envelope"></i> Subscribe</button>
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
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /NEWSLETTER -->
<footer id="footer">
			<!-- pie de página superior -->
			<div class="section"  >
				<!-- contenedor -->
				<div class="container">
					<!-- fila -->
					<div class="row">
						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Sobre Nosotros</h3>
								<p>Desde 1991, dando servicios y cada vez mejores soluciones.</p>
								<ul class="footer-links">
									<li><a href="#"><i class="fa fa-map-marker"></i>Av. Díaz Vélez 1240 (C.P. 1702)Ciudadela, Buenos Aires, Argentina</a></li>
									<li><a href="#"><i class="fa fa-phone"></i>(+54)11 4488 4489</a></li>
									<li><a href="#"><i class="fa fa-envelope-o"></i>ventas@sistemasenergeticos.com.ar</a></li>
								</ul>
							</div>
						</div>

						<div class="col-md-3 col-xs-6">
								<div class="footer">
									<h3 class="footer-title">Categorías</h3>
									<ul class="footer-links">
										<?php foreach ($categorias as $categoria): ?>
											<li><a href="#"><?php echo htmlspecialchars($categoria['name']); ?></a></li>
										<?php endforeach; ?>
									</ul>
								</div>
							</div>


						<div class="clearfix visible-xs"></div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Información</h3>
								<ul class="footer-links">
									<li><a href="https://www.sistemasenergeticos.com.ar/nosotros">Sobre Nosotros</a></li>
									<li><a href="https://www.sistemasenergeticos.com.ar/contacto">Contáctanos</a></li>
									<li><a href="https://www.sistemasenergeticos.com.ar/cookies.php">Política de Privacidad</a></li>
									<li><a href="#">Términos y Condiciones</a></li>
								</ul>
							</div>
						</div>

						<div class="col-md-3 col-xs-6">
							<div class="footer">
								<h3 class="footer-title">Servicio</h3>
								<ul class="footer-links">
									<li><a href="userpanel.php">Mi Cuenta</a></li>
									<li><a href="carrito.php">Ver Carrito</a></li>
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
		<script src="./js/jquery.min.js"></script>
		<script src="./js/bootstrap.min.js"></script>
		<script src="./js/slick.min.js"></script>
		<script src="./js/nouislider.min.js"></script>
		<script src="./js/jquery.zoom.min.js"></script>
		<script src="./js/main.js"></script>
		<script>
document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', function() {
        console.log("Botón clickeado"); // Añadir un log para ver si el evento se dispara
        const productId = this.getAttribute('data-product-id');
        const quantity = 1;

        // Solicitud fetch
        fetch('addcarrito.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`,
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes('Error')) { // Verificar si la respuesta indica un error
                window.location.href = 'login.php'; // Redirige al login en caso de error
            } else {
                alert(data); // Mostrar mensaje de éxito o manejar la respuesta
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.location.href = 'login.php'; // Redirige al login en caso de error en la solicitud
        });
    });
});
</script>
