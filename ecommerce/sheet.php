<?php
include('config/checksession.php'); // Incluye el archivo que recupera los datos de los productos
include 'config/database.php'; // Incluye tu archivo de configuración con PDO


// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    // Si no está logueado, redirigir a login.php
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario de la sesión
$user_id = $_SESSION['user_id'];

// Obtener el estado del usuario de la base de datos
$stmt = $conn->prepare("SELECT status, solicitud_verificacion FROM clientes WHERE id = :id");
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Comprobar si el usuario está verificado
$is_verified = $user && $user['status'] === 'verificado';
$solicitud_enviada = $user && $user['solicitud_verificacion'] === 'si'; // Verifica si la solicitud ha sido enviada
?>


<?php include 'assets/includes/head.php';?>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
</head>
<body>
    <!-- HEADER -->
    <?php include 'assets/includes/header.php'; ?>
    <!-- HEADER -->
    <div class="section">
        <div class="container" style="overflow: hidden;">
        <?php if ($is_verified): ?>
            <!-- Mostrar el iframe si el usuario está verificado -->
            <iframe 
                src="https://docs.google.com/spreadsheets/d/1G-GWOiycFrzNmdCrNz_TFA13QH2H2CFj/preview" 
                width="100%" 
                height="600px"
                frameborder="0">
            </iframe>
        <?php else: ?>
            <?php if ($solicitud_enviada): ?>
                <div class="alert alert-success" role="alert">
                    Tu solicitud de verificación ha sido enviada. Por favor espera a que sea procesada.
                </div>
            <?php else: ?>
                <!-- Mostrar el mensaje y el botón si el usuario no está verificado y no ha enviado la solicitud -->
                <div class="alert alert-info" role="alert">
                    <p>Para poder ver la lista de precios, primero necesitas verificar tu cuenta.</p>
                </div>

                <button class="primary-btn cta-btn" onclick="window.location.href='config/solicitar_verificacion.php';">Solicitar Verificación</button>
            <?php endif; ?>
        <?php endif; ?>

        </div>
    </div>
    <!-- PIE DE PÁGINA -->
    <?php include 'assets/includes/footer.php'; ?>
</body>
</html>
