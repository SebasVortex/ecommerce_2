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
											<li><a href="store.php?category%5B%5D=<?php echo htmlspecialchars($categoria['id']); ?>"><?php echo htmlspecialchars($categoria['name']); ?></a></li>
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
									<li><a href="https://www.sistemasenergeticos.com.ar/contacto">Ayuda</a></li>
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
document.querySelectorAll('.add-to-cart-btn, .add-to-wishlist').forEach(button => {
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


			<!-- codigo para la busqueda -->
			<script>
document.addEventListener('DOMContentLoaded', () => {
    const resultsContainer = document.getElementById('results-container');
    const searchInput = document.getElementById('search-input');

    if (!resultsContainer || !searchInput) {
        console.error('Elementos necesarios no encontrados en el DOM.');
        return;
    }

    // Función para escapar caracteres especiales en texto HTML para prevenir XSS
    function escapeHTML(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function searchProducts(searchTerm) {
        fetch(`config/buscar_productos.php?search=${encodeURIComponent(searchTerm)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin' // Asegura que las cookies se envíen solo con solicitudes de la misma origen
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('La respuesta de la red no fue satisfactoria');
                }
                return response.json();
            })
            .then(data => {
                resultsContainer.innerHTML = '';

                data.forEach(item => {
                    // Escapamos datos recibidos para prevenir XSS
                    const productHtml = `
                        <div class="result-item" style="display: flex; padding: 1.5rem; border-bottom: 1px solid #ccc; max-width: 535px; width: 100%;">
                            <img src="assets/images/${escapeHTML(item.imagen)}" alt="${escapeHTML(item.name)}" style="width: 100px; height: auto; margin-right: 15px;">
                            <div style="display: flex; flex-direction: column; justify-content: center;">
                                <h3 style="font-size: 18px;">
                                    <a href="product_detalle.php?id=${encodeURIComponent(item.id)}">${escapeHTML(item.brand_name)} - ${escapeHTML(item.name)}</a>
                                </h3>
                                <p>Categoria: ${escapeHTML(item.category_name)}</p>
                            </div>
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
            resultsContainer.style.display = 'block'; // Mostrar resultados
        } else {
            resultsContainer.innerHTML = ''; // Limpiar resultados si no hay término de búsqueda
            resultsContainer.style.display = 'none'; // Ocultar resultados
        }
    });

    // Detectar clics fuera del contenedor de resultados y del campo de búsqueda
    document.addEventListener('click', (event) => {
        const isClickInside = resultsContainer.contains(event.target) || searchInput.contains(event.target);
        if (!isClickInside) {
            resultsContainer.style.display = 'none'; // Ocultar resultados si se hace clic fuera
        }
    });

    // Mostrar resultados nuevamente si se hace clic en el campo de búsqueda
    searchInput.addEventListener('focus', () => {
        if (searchInput.value.trim() !== '') {
            resultsContainer.style.display = 'block';
        }
    });
});

</script>

<script>
    // Manejo del clic en el botón
    document.querySelectorAll('.quick-view').forEach(function(button) {
        button.addEventListener('click', function() {
            var productId = this.getAttribute('data-product-id');
            window.location.href = 'product_detalle.php?id=' + productId;
        });
    });

</script>

<script>
document.getElementById('userType').addEventListener('change', function() {
    const formConsumidor = document.getElementById('form-consumidor');
    const formEmpresa = document.getElementById('form-empresa');
    const submitGroup = document.getElementById('submit-group');
    const userType = this.value;

    if (userType === 'consumidor') {
        formConsumidor.style.display = 'block';
        formEmpresa.style.display = 'none';
        submitGroup.style.display = 'block';

        document.querySelectorAll('#form-consumidor input').forEach(input => {
            input.required = true;
        });

        document.querySelectorAll('#form-empresa input').forEach(input => {
            input.required = false;
        });
    } else if (userType === 'empresa') {
        formConsumidor.style.display = 'none';
        formEmpresa.style.display = 'block';
        submitGroup.style.display = 'block';

        document.querySelectorAll('#form-empresa input').forEach(input => {
            input.required = true;
        });

        document.querySelectorAll('#form-consumidor input').forEach(input => {
            input.required = false;
        });
    } else {
        formConsumidor.style.display = 'none';
        formEmpresa.style.display = 'none';
        submitGroup.style.display = 'none';
    }
});

// Event listener para capturar los cambios en los campos de entrada
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('input', function() {
        console.log(`${this.name}: ${this.value}`);
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateSubtotal() {
        let subtotal = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const quantityElement = item.querySelector('input[name="quantity"]');
            const priceElement = item.querySelector('.text-right p');
            if (quantityElement && priceElement) {
                const quantity = parseInt(quantityElement.value);
                const price = parseFloat(priceElement.textContent.replace('$', '').replace(/,/g, ''));
                subtotal += quantity * price;
            }
        });
        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
    }

    function updateTotalItems() {
        let totalItems = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const quantityElement = item.querySelector('input[name="quantity"]');
            if (quantityElement) {
                totalItems += parseInt(quantityElement.value);
            }
        });
        const totalItemsElement = document.getElementById('total-it');
        if (totalItemsElement) {
            totalItemsElement.textContent = totalItems + ' Item(s)';
        }
    }

    function updateQuantity(productId, change) {
        const quantityElement = document.getElementById('quantity-' + productId);
        if (quantityElement) {
            let currentQuantity = parseInt(quantityElement.value);
            currentQuantity += change;
            if (currentQuantity < 0) currentQuantity = 0; 
            quantityElement.value = currentQuantity;

            const priceElement = document.getElementById('price-' + productId);
            const totalElement = document.getElementById('total-' + productId);
            if (priceElement && totalElement) {
                const price = parseFloat(priceElement.textContent.replace('$', '').replace(/,/g, ''));
                totalElement.textContent = '$' + (price * currentQuantity).toFixed(2);
            }

            updateSubtotal();
            updateTotalItems();

            fetch('carrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    'update_quantity': true,
                    'product_id': productId,
                    'quantity': currentQuantity
                })
            }).catch(error => console.error('Error:', error));
        }
    }

    document.querySelectorAll('.plus').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.closest('.cart-item').id.split('-').pop();
            updateQuantity(productId, 1);
        });
    });

    document.querySelectorAll('.minus').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.closest('.cart-item').id.split('-').pop();
            updateQuantity(productId, -1);
        });
    });

    updateSubtotal();
    updateTotalItems();
});

</script>
<!-- Incluye jQuery UI para el control deslizante -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">