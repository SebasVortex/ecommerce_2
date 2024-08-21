<!-- NEWSLETTER -->
<div id="newsletter" class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<div class="newsletter">
							<p>Suscribite a nuestro <strong>NEWSLETTER</strong></p>
							<form>
								<input class="input" type="email" placeholder="Example@gmail.com">
								<button class="newsletter-btn"><i class="fa fa-envelope"></i> Subscribite</button>
							</form>
							<ul class="newsletter-follow">
								<li>
									<a href="https://www.youtube.com/@sistemasenergeticos"><i class="fa fa-youtube"></i></a>
								</li>
								<li>
									<a href="https://www.instagram.com/sistemasenergeticossa/"><i class="fa fa-instagram"></i></a>
								</li>
								<li>
									<a href="https://www.linkedin.com/company/sistemas-energeticos-s-a-/"><i class="fa fa-linkedin"></i></a>
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

					<!-- carrito agregar -->
		<script>
			document.querySelectorAll('.add-to-cart-btn').forEach(button => {
				button.addEventListener('click', function() {
					console.log("Botón clickeado"); // Añadir un log para ver si el evento se dispara
					const productId = this.getAttribute('data-product-id');
					const quantityInput = document.getElementById(`quantity-${productId}`);
					const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

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





			<!-- codigo para la busqueda -->
			<script>
document.addEventListener('DOMContentLoaded', () => {
    const resultsContainer = document.getElementById('results-container');
    const searchInput = document.getElementById('search-input');

    if (!resultsContainer || !searchInput) {
        console.error('Elementos necesarios no encontrados en el DOM.');
        return;
    }

    function searchProducts(searchTerm) {
        fetch(`config/buscar_productos.php?search=${encodeURIComponent(searchTerm)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                resultsContainer.innerHTML = '';

                data.forEach(item => {
                    const productHtml = `
                        <div class="result-item" style="padding: 1.5rem; border-bottom: 1px solid #ccc; max-width: 535px;">
                            <h3><a href="product_detalle.php?id=${item.id}">${item.brand_name} - ${item.name}</a></h3>
                            <p>Categoria: ${item.category_name}</p>
                            <p>$${parseFloat(item.price).toFixed(2)}</p>
                        </div>
                    `;

                    resultsContainer.innerHTML += productHtml;
                });
            })
            .catch(error => console.error('Error al buscar productos:', error));
    }

    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.trim();
        if (searchTerm) {
            searchProducts(searchTerm);
        } else {
            resultsContainer.innerHTML = ''; // Limpiar resultados si no hay término de búsqueda
        }
    });
});

</script>


