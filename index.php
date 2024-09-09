
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

    <!-- CSS de Swiper -->
	<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
</head>
	<body>
		
		<!-- HEADER -->
		<?php include 'assets/includes/header.php';?>
		<!-- HEADER -->
		 

		<!-- SECTION -->
		<div class="section">
		<!-- container -->
		<div class="container" style="overflow: hidden;">
            <!-- Controles de navegación -->
			 
				<div class="swiper-container">
					<div class="control-slider">
						<div class="custom-button-prev"><span class="material-symbols-outlined">chevron_left</span></div>
						<div class="custom-button-next"><span class="material-symbols-outlined">chevron_right</span></div>
					</div>
					<div class="swiper-wrapper"><!-- Slide 1 -->

					<div class="swiper-slide" id="slide-2">	<!-- Slide 2 -->
							<div class="banner-2">
								<div class="overlay-2">
									<div class="content-2">
									<h1>¿Probaste nuestro dimensionador?</h1>
										<p>Dimensioná tu sistema en tan solo unos pasos</p>
										<span>TOTALMENTE GRATIS</span>
										<a target="_blank" href="https://www.sistemasenergeticos.com.ar/dimensionadorsolar">PROBAR</a>
									</div>
								</div>
							</div>
						</div>


						<div class="swiper-slide" id="slider-3"><!-- Slide 3 -->
							<div class="banner-3">
								<div class="content-3">
									<div class="header-3">
										<div class="ribbon">
											<h2>Nuevo lanzamiento!</h2>
										</div>
									</div>
									<div class="description-3">
										<h1>Micro-inversor GoodWe</h1>
										<ul>
											<li><span style="color:#d10024;">✔</span> Soporta hasta 4 paneles</li>
											<li><span style="color:#d10024;">✔</span>  WiFi y Bluetooth integrados</li>
										</ul>
									</div>
								</div>
								<div class="product-image-3">
									<img src="assets/images/m-inversor.png" alt="Micro-inversor GoodWe">
								</div>
							</div>
						</div>
						<div class="swiper-slide" id="dimensionador">
                            <div class="slide-1">
                                <div class="first-p">
                                    <p>¿Queres acceder a nuestra lista de precios?</p>
                                </div>
                                <div class="second-s">  
                                    <img src="assets/images/verified.png" alt="">
									<a href="sheet.php">Verificate</a>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
				<div class="swiper-pagination"></div>
			</div>
		</div>
			<div class="container">
				<!-- row -->
				<div class="row">
					<!-- shop -->
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<div class="shop-img">
							<img src="assets/images/bannersh.png" alt="">
							</div>
							<div class="shop-body">
								<h3>Sistemas<br>hibridos</h3>
								<a href="store.php?category%5B%5D=1" class="cta-btn">Ver más <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					<!-- /shop -->

					<!-- shop -->
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<div class="shop-img">
								<img src="assets/images/bateriabanner.png" alt="">
							</div>
							<div class="shop-body">
								<h3>Baterías<br>de litio</h3>
								<a href="store.php?category%5B%5D=9" class="cta-btn">Ver más <i class="fa fa-arrow-circle-right"></i></a>
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
								<a href="store.php?category%5B%5D=6" class="cta-btn">Ver más <i class="fa fa-arrow-circle-right"></i></a>
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
                        <span class="sale">-<?php echo htmlspecialchars($producto['discount']); ?><i class="fa-solid fa-tag fa-shake"></i>%</span>
                    <?php endif; ?>
                    <?php if ($producto['new']): ?>
                        <span class="new">Nuevo <i class="fa-solid fa-exclamation fa-shake" style="--fa-animation-duration: 2s;"></i></span>
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

            <div class="product-rating"></div>

            <div class="product-btns">
                <button class="add-to-wishlist" onclick="addToCart(<?php echo htmlspecialchars($producto['id']); ?>)"><i class="fa-solid fa-cart-plus fa-flip" style="--fa-animation-duration: 3s;"></i><span class="tooltipp">Añadir al carrito</span></button>
                <button class="quick-view" data-product-id="<?php echo $producto['id']; ?>">
                    <i class="fa fa-eye fa-beat" style="--fa-animation-duration: 2s;"></i><span class="tooltipp">Ver más</span>
                </button>
            </div>
        </div>
        <div class="add-to-cart">
		<button class="add-to-cart-btn" onclick="addToCart(<?php echo htmlspecialchars($producto['id']); ?>)">
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
			<!-- container productos dentro iria un row como el de arriba -->
			<div class="container">
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
    document.querySelector(".hot-deal").innerHTML = "<h2 class='text-uppercase'>¡Hasta 35% OFF en Inversores!</h2><p>Descubri nuestros nuevos modelos con grandes descuentos.</p><a class='primary-btn cta-btn' href='store.php?category%5B%5D=5'>Ver ofertas</a>";

    // Cambiar el fondo de la sección
    var hotDealSection = document.querySelector("#hot-deal.section");
    hotDealSection.style.backgroundColor = "#000000";
    hotDealSection.style.backgroundImage = "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100%25' height='100%25' viewBox='0 0 1600 800'%3E%3Cg %3E%3Cpolygon fill='%23220000' points='1600 160 0 460 0 350 1600 50'/%3E%3Cpolygon fill='%23440000' points='1600 260 0 560 0 450 1600 150'/%3E%3Cpolygon fill='%23660000' points='1600 360 0 660 0 550 1600 250'/%3E%3Cpolygon fill='%23880000' points='1600 460 0 760 0 650 1600 350'/%3E%3Cpolygon fill='%23A00' points='1600 800 0 800 0 750 1600 450'/%3E%3C/g%3E%3C/svg%3E\")";
    hotDealSection.style.backgroundSize = "cover";
    hotDealSection.style.backgroundPosition = "center";
    hotDealSection.style.backgroundRepeat = "no-repeat";
    hotDealSection.style.paddingTop = "10rem";
    hotDealSection.style.paddingBottom = "10rem";
}



}, 1000);
</script>

<!-- JavaScript de Swiper -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
  var swiper = new Swiper('.swiper-container', {
    slidesPerView: 1,
    spaceBetween: 25,
    loop: true,
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
		nextEl: '.custom-button-next', // Apunta al nuevo botón personalizado
		prevEl: '.custom-button-prev', // Apunta al nuevo botón personalizado
    },
    autoplay: {
      disableOnInteraction: false,
    },
    effect: 'slide', // Aquí puedes cambiar el efecto a 'fade', 'cube', etc.
  });
</script>
		<!-- /PIE DE PÁGINA -->
		<style>
    .swiper-container {
		width: 100%;
		height: 500px;
    }
    .swiper-slide {
		text-align: center;
		width: 100%;
		font-size: 18px;
		background: #fff;
		display: flex !important;
		justify-content: center;
		align-items: center;
		height: 500px;
		background-position: center;
		background-repeat: no-repeat;
		background-size: cover;
    }
	.swiper-slide .btn{
		align-self: end;
		margin-bottom: 50px;
		margin-left: auto;
		margin-right: 4rem;
	}
	.swiper-wrapper{
		width: 100%;
	}
	.swiper-pagination{
		position: relative !important;
		margin-top: 25px;
	}
	/* Estilos para los botones de navegación personalizados */
	.custom-button-next,
	.custom-button-prev {
		cursor: pointer;
		width: 40px;
		height: 40px;
		color: #ffffff;

		display: flex;
		justify-content: center;
		align-items: center;
		border-radius: 25%; /* Hace los botones redondos */
		position: relative; /* Posicionamiento absoluto para ubicarlos en el contenedor */
		top: 50%; /* Centrados verticalmente */
		font-size: 28px !important;
		z-index: 10; /* Asegura que estén sobre otros elementos */
		transition: background-color 0.3s; /* Transición para un efecto suave al pasar el ratón */
}
	.custom-button-next span,
	.custom-button-prev  span{
		font-size: 48px !important;
	}
	.custom-button-next:hover,
	.custom-button-prev:hover {
		border: 2px solid #ffffff;
	  	background-color: #d91e22; /* Cambio de color al pasar el ratón */
		color: white;
	}
	.custom-button-next {
		margin-left: auto;
	}
	.custom-button-prev {
		margin-right: auto;
	}
	.control-slider {
		display: flex;
		justify-content: center;
		align-items: center;
		position: relative;
		top: 55%;
	}
	#dimensionador{
		background-color: #D51939;
	}
.second-s a{
    border: 1px solid transparent;
    padding: 10px 54px;
    color: #D51939;
    background-color: white;
    text-transform: uppercase;
    font-weight: 700;
    border-radius: 40px;
    -webkit-transition: 0.2s all;
    transition: 0.2s all;
    font-size: 25px;
}
.first-p p{
    color: white;
    font-size: 34px;
    font-weight: 600;
    margin-bottom: 60px;
}
.second-s img{
    width: 150px;
}
.second-s{
    display: flex;
    align-items: center;
    justify-content: space-around;
}
/* Estilo para el slide 2 */
#slide-2 {
    position: relative;
}
.swiper-notification{
	display: none;
}
.banner-2 {
	position: relative;
    width: 100%;
    height: 100%; /* Toda la altura de la pantalla */
    background-image: url('assets/images/prueba.png'); /* Imagen de fondo */
    background-size: cover;
    background-position: center;
    display: flex;
    justify-content: center;
    align-items: center;
}

.overlay-2 {
	position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, rgba(0, 0, 0, 0.8), rgba(54, 99, 171, 0.6)); 
    display: flex;
    justify-content: center;
    align-items: center;
}

.content-2 {
    text-align: center;
    display: flex;
    z-index: 2;
    color: white !important;
    align-items: center;
    flex-direction: column;
}
.content-2 a{
	display: flex;
	justify-content: center;
	align-items: center;
	width: 85%;
	max-width: 295px;
	padding: 1rem;
	color: #d91e22;
	background-color: #ffffff;
	border: 2px solid #d91e22;
	margin-top: 25px;
	border-radius: 25px;
	font-weight: 600;
}

.content-2 h1 {
	font-size: 3.5rem;
    font-weight: bold;
    margin-bottom: 20px;
	color: white ;
}

.content-2 p {
    font-size: 2.2rem;
    margin-bottom: 10px;
}

.content-2 span {
	font-size: 2.5rem;
    font-weight: bold;
    text-transform: uppercase;
    border-bottom: 2px solid white; /* Subrayado */
}

div#slider-3 {
    background-color: #000;
    display: flex;
}
.banner-3 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    color: white;
    background-color: #000; /* Fondo negro */
}

.content-3 {
    flex: 1;
	height: 320px;
}

.header-3 .ribbon {
    background-color: #E30613;
    padding: 10px 18px;
    border-top-right-radius: 35px;
    border-bottom-right-radius: 35px;
    color: white;
    margin-bottom: 70px;
}

.header-3 .ribbon h2 {
    margin: 0;
    font-size: 3.5rem;
    font-weight: bold;
	color: white;
}

.description-3 h1 {
    font-size: 2rem;
    margin-bottom: 20px;
	color: white;
}
.description-3 {
	display: flex;
    flex-direction: column;
    align-items: center;
}
.description-3 ul {
    font-size: 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.description-3 li {
    margin-bottom: 10px;
    font-weight: 300;
}

.product-image-3 {
    flex: 1;
    text-align: right;
}

.product-image-3 img {
    max-width: 450px;
    height: auto;
	margin-right: 4rem;
}
/* Estilo general para la paginación */
.swiper-pagination {
    position: absolute; /* Asegúrate de que esté en la posición correcta */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10; /* Asegura que esté encima de otros elementos */
}

/* Estilo para los puntos de paginación */
.swiper-pagination-bullet {
    width: 12px; /* Tamaño de los puntos */
    height: 12px;
    background-color: #fff; /* Color de los puntos */
    border-radius: 50%; /* Hace los puntos redondos */
    opacity: 0.6; /* Opacidad de los puntos */
    transition: opacity 0.3s, background-color 0.3s; /* Transiciones suaves */
	border: 2px solid #ffffff;
}

/* Estilo para el punto activo */
.swiper-pagination-bullet-active {
    background-color: #d91e22; /* Color del punto activo */

    opacity: 1; /* Opacidad del punto activo */
}

/* Estilo adicional para la paginación cuando se está deslizando */
.swiper-pagination-bullet:hover {
    background-color: #0056b3; /* Color de los puntos al pasar el ratón */
}


@media (max-width: 700px){
	/* Estilo para los puntos de paginación */
	.swiper-pagination-bullet {
		border: 2px solid black;
	}
    .second-s {
        display: flex;
        align-items: center;
        flex-direction: column-reverse;
    }
    .second-s img{
        width: 100px;
    }
    .second-s a{
        padding: 8px 40px;
        font-size: 20px;
        margin-bottom: 40px;
    }
    .first-p p {
        font-size: 28px;
        margin-bottom: 40px;
    }
    .banner-3 {
        flex-direction: column-reverse; /* Coloca la imagen y el contenido en columna */
        align-items: center; /* Centra el contenido */
        text-align: center;
    }

    .product-image-3 img {
        max-width: 250px; /* Reduce el tamaño de la imagen */
        margin-top: 20px; /* Añade espacio superior */
		margin-right: 0rem;
		margin-bottom: 25px;
    }

    .header-3 .ribbon h2 {
        font-size: 2rem; /* Ajusta el tamaño del texto */
    }

    .description-3 h1 {
        font-size: 1.5rem;
    }

    .description-3 ul {
        font-size: 1.2rem;
    }
	.control-slider{
		display: none;
	}
	.content-3{
	width: 100%;
	}
	.header-3 .ribbon {
     margin-bottom: 35px !important;
     border-radius: 0px !important;

}
.description-3 {
    display: flex;
    flex-direction: column;
    margin-bottom: 65px;
    align-items: center;
}
.swiper-pagination {
    position: relative !important;
}
}
    </style>
	<?php
// Verificar si el parámetro 'pedido' existe en la URL y si es igual a 'realizado'
if (isset($_GET['pedido']) && $_GET['pedido'] === 'realizado') {
    echo "
    <script>
        Swal.fire({
            title: '¡Pedido realizado con éxito!',
            text: 'Tu pedido ha sido procesado correctamente.',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    </script>";
}
?>
	</body>
</html>


