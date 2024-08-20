<?php

// Incluir el archivo de configuración de la base de datos
include 'config/database.php'; 

// Incluir el archivo para verificar la sesión
include 'config/checksession.php';

// Verificar si el user_id está presente en la sesión
if (!isset($_SESSION['user_id'])) {
    // Redirigir al login.php si no hay user_id
    header("Location: login.php");
    exit(); // Terminar el script después de redirigir
}

$user_id = $_SESSION['user_id'];

// Obtener los productos en el carrito desde la base de datos
$query = "SELECT p.id, p.name, p.price, p.imagen, c.quantity 
          FROM carrito c 
          JOIN productos p ON c.product_id = p.id 
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'assets/includes/head.php';
?>
</head>
	<body>
		<!-- HEADER -->
		<?php include 'assets/includes/header.php';?>
		<!-- /HEADER -->

		<!-- BREADCRUMB -->
		<div id="breadcrumb" class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<h3 class="breadcrumb-header">Checkout</h3>
						<ul class="breadcrumb-tree">
							<li><a href="index.php">Inicio</a></li>
							<li class="active">Checkout</li>
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
        <div class="col-md-7">
            <!-- Billing Details -->
            <div class="billing-details">
                <div class="section-title">
                    <h3 class="title">Datos Personales</h3>
                </div>
                <form action="config/processcheckoutt.php" method="POST">
                    <div class="form-group">
                        <input class="input" type="text" name="first-name" placeholder="Nombre" required>
                    </div>
                    <div class="form-group">
                        <input class="input" type="text" name="last-name" placeholder="Apellido" required>
                    </div>
                    <div class="form-group">
                        <input class="input" type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input class="input" type="text" name="address" placeholder="Dirección" required>
                    </div>
                    <div class="form-group">
                        <input class="input" type="text" name="city" placeholder="Ciudad" required>
                    </div>
                    <div class="form-group">
                        <input class="input" type="text" name="country" placeholder="País" required>
                    </div>
                    <div class="form-group">
                        <input class="input" type="text" name="zip-code" placeholder="Código Postal" required>
                    </div>
                    <div class="form-group">
                        <input class="input" type="tel" name="tel" placeholder="Teléfono" required>
                    </div>
					
            <!-- Order notes -->
                    <div class="form-group">
                        <textarea class="input" name="notas" placeholder="Notas de Aclaración" rows="4"></textarea>
                    </div>
            <!-- /Order notes -->
            </div>
        </div>

        <!-- Order Details -->
        <div class="col-md-5 order-details">
            <div class="section-title text-center">
                <h3 class="title">Tu orden</h3>
            </div>
            <div class="order-summary">
                <div class="order-col">
                    <div><strong>PRODUCTO</strong></div>
                    <div><strong>TOTAL</strong></div>
                </div>
                <?php if (!empty($items)): ?>
                <div class="order-products">
                <?php foreach ($items as $item): ?>
                    <div class="order-col">
                        <div><?php echo htmlspecialchars($item['quantity']); ?>x <?php echo htmlspecialchars($item['name']); ?></div>
                        <div>$<?php echo number_format($item['price'], 2); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    Tu carrito está vacío
                    <?php endif; ?>
                <div class="order-col">
                    <div>Envío</div>
                    <div><strong>GRATIS</strong></div>
                </div>
                <div class="order-col">
                    <div><strong>TOTAL</strong></div>
                    <div><strong class="order-total">$<?php echo isset($_SESSION['user_id']) ? number_format($total, 2) : '0.00'; ?></strong></div>
                </div>
            </div>

            <!-- Moved the submit button to the checkout section -->
			<button type="submit" class="primary-btn order-submit">Finalizar Compra</button>
			</form>
        </div>
        <!-- /Order Details -->
    </div>
    <!-- /row -->
</div>
<!-- /container -->

		</div>
		<h1>Importante: Procedimiento de Compra</h1>
		<p>Por razones de seguridad, no aceptamos pagos a través de medios electrónicos en nuestra tienda en línea. Sin embargo, le invitamos a completar el formulario de pedido para iniciar el proceso de compra.

Una vez que haya enviado su formulario con los detalles de su pedido, uno de nuestros asistentes de ventas se pondrá en contacto con usted para finalizar la transacción y coordinar el pago y la entrega.

Agradecemos su comprensión y esperamos poder asistirle personalmente para garantizar una experiencia de compra segura y satisfactoria.

</p>
		<!-- /SECTION -->

		    		<!-- PIE DE PÁGINA -->
					<?php include 'assets/includes/footer.php';?>
		            <!-- /PIE DE PÁGINA -->
	</body>
</html>
