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

// Ahora $items contiene los productos en el carrito junto con su precio y otros detalles
// Cargar el carrito de la base de datos a la sesión
function cargarCarrito($userId) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM carrito WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $carrito = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $_SESSION['cart_items'] = $carrito;
}

// Llamar a esta función al iniciar sesión o cuando sea necesario
if (isset($_SESSION['user_id'])) {
    cargarCarrito($_SESSION['user_id']);
}

?>

<?php include 'assets/includes/head.php'; ?>
<style>
.custom-button {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 10px 20px;
  font-size: 16px;
  text-transform: uppercase;
  font-weight: 700;
  color: #fff;
  background-color: #D10024;
  border: none;
  border-radius: 35px;
  overflow: hidden;
  cursor: pointer;
  transition: background-color 0.3s ease;
  min-width: 150px;
  height: 45px;
}

.custom-button .content-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  position: relative;
}

.custom-button .button-text {
  z-index: 2;
  opacity: 1;
  transition: opacity 0.3s ease;
}
.red{
    color: #D10024;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
}
.custom-button .checkmark,
.custom-button .error-x {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 2;
  font-size: 18px;
  line-height: 1;
  display: none;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.custom-button.loading .button-text {
  opacity: 0;
}

.custom-button.loading .checkmark,
.custom-button.loading .error-x {
  display: block;
}

.custom-button.loading.success .checkmark {
  opacity: 1;
}

.custom-button.loading.error .error-x {
  opacity: 1;
}

.custom-button::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  background-color: #A8001D;
  z-index: 1;
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.5s ease-in-out;
}

.custom-button.loading::after {
  transform: scaleX(1);
}
</style>
</head>
<body>
    <!-- HEADER -->
    <?php include 'assets/includes/header.php'; ?>
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
    <!-- Important Notice -->
    <div class="container mt-5">
        <div class="alert alert-info text-center">
            <h1 class="important-heading">Importante: Procedimiento de Compra</h1>
            <p class="important-text">Por razones de seguridad, no aceptamos pagos a través de medios electrónicos en nuestra tienda en línea. Sin embargo, le invitamos a completar el formulario de pedido para iniciar el proceso de compra. Una vez que haya enviado su formulario con los detalles de su pedido, uno de nuestros asistentes de ventas se pondrá en contacto con usted para finalizar la transacción y coordinar el pago y la entrega. Agradecemos su comprensión y esperamos poder asistirle personalmente para garantizar una experiencia de compra segura y satisfactoria.</p>
        </div>
    </div>
    <!-- /Important Notice -->
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
                            <h3 class="title">Completa tus datos</h3>
                        </div>
                        <form action="config/processcheckoutt.php" method="POST" id="checkout-form">
                            <div class="form-group">
                                <label for="userType">Selecciona una opción:</label>
                                <select id="userType" name="userType" class="input" required>
                                    <option value="" disabled selected>Seleccione...</option>
                                    <option value="consumidor">Consumidor Final</option>
                                    <option value="empresa">Empresa</option>
                                </select>
                            </div>

                                <!-- Formulario para Consumidor Final -->
                                <div id="form-consumidor" style="display: none;">
                                    <div class="form-group">
                                        <input class="input" type="text" name="consumidor-first-name" placeholder="Nombre">
                                    </div>
                                    <div class="form-group">
                                        <input class="input" type="text" name="consumidor-last-name" placeholder="Apellido">
                                    </div>
                                    <div class="form-group">
                                        <input class="input" type="email" name="consumidor-email" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <input class="input" type="tel" name="consumidor-tel" placeholder="Teléfono">
                                    </div>
                                </div>

                                <!-- Formulario para Empresa -->
                                <div id="form-empresa" style="display: none;">
                                    <div class="form-group">
                                        <input class="input" type="text" name="empresa-razon-social" placeholder="Razón Social">
                                    </div>
                                    <div class="form-group">
                                        <input class="input" type="text" name="empresa-cuit" placeholder="CUIT">
                                    </div>
                                    <div class="form-group">
                                        <input class="input" type="email" name="empresa-email" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <input class="input" type="tel" name="empresa-tel" placeholder="Teléfono">
                                    </div>
                                    <div class="form-group">
                                        <input class="input" type="text" name="empresa-persona-contacto" placeholder="Persona de contacto">
                                    </div>
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
                            <p class="red">Tu carrito está vacío <i class="fa-solid fa-cart-plus fa-bounce"></i></p>
                        <?php endif; ?>
                        <!--
                        <div class="order-col">
                            <div>Envío</div>
                            <div><strong>GRATIS</strong></div>
                        </div>
                        -->
                        <div class="order-col">
                            <div><strong>TOTAL</strong></div>
                            <div><strong class="order-total">$<?php echo isset($_SESSION['user_id']) ? number_format($total, 2) : '0.00'; ?></strong></div>
                        </div>
                    </div>

                    <!-- Moved the submit button to the checkout section -->
                        <!-- Botón de Envío -->
                        <div class="form-group" id="submit-group" style="display: none;">
                            <button id="customButton" type="submit" class="custom-button">
                                <div class="content-wrapper">
                                    <span class="button-text">Finalizar compra</span>
                                    <span class="checkmark"><span class="material-symbols-outlined">task_alt</span></span>
                                    <span class="error-x"><span class="material-symbols-outlined">cancel</span></span>
                                </div>
                            </button>
                        </div>

                    </form>
                </div>
                <!-- /Order Details -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->

    <!-- PIE DE PÁGINA -->
    <?php include 'assets/includes/footer.php'; ?>
    <!-- /PIE DE PÁGINA -->
    <script>
        document.getElementById('checkout-form').addEventListener('submit', function (e) {
            e.preventDefault(); // Prevenir el envío inmediato del formulario
            var button = document.getElementById('customButton');
            button.classList.add('loading');
            button.disabled = true;

            // Verificar si el carrito está vacío
            var carritoVacio = <?php echo empty($items) ? 'true' : 'false'; ?>; // Comprobación basada en PHP

            setTimeout(function () {
                if (carritoVacio) {
                    button.classList.add('error');
                    button.querySelector('.checkmark').style.display = 'none';
                    button.querySelector('.error-x').style.display = 'inline-block';
                } else {
                    button.classList.add('success');
                    button.querySelector('.checkmark').style.display = 'inline-block';
                    button.querySelector('.error-x').style.display = 'none';
                }

                // No se envía el formulario si el carrito está vacío (se muestra el error)
                if (!carritoVacio) {
                    e.target.submit();
                } else {
                    button.disabled = false; // Reactivar el botón si el carrito está vacío
                    button.classList.remove('loading');
                }
            }, 1500); // Simulación del tiempo de "carga" de 1.5 segundos
        });
    </script>
</body>
</html>
