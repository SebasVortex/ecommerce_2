<?php
// Incluir la configuración de la base de datos y la verificación de sesión
include('config/database.php');
include('config/checksession.php');

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_id'];

try {
    // Consultar la información del usuario
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        // Si no se encuentra el usuario, redirige al inicio de sesión
        header("Location: login.php");
        exit();
    }

    // Consultar información adicional si es necesario (como pedidos, dirección, etc.)
    // ...

} catch (PDOException $e) {
    error_log('Error en la consulta: ' . $e->getMessage());
    echo "Error al obtener la información del usuario.";
}
?>
<?php include 'assets/includes/head.php';?>
<title>Mi cuenta - <?php echo htmlspecialchars($user['username']); ?></title>
<style>
    
</style>
</head>
<body>
    <!-- HEADER -->
    <?php include 'assets/includes/header.php';?>
    <!-- HEADER -->
    <div class="container mt-5">
        <h1 class="mb-4"> Hola, <?php echo htmlspecialchars($user['username']); ?></h1>
        
        <div class="row">
            <div class="col-md-4 text-center">
                <!-- Mostrar imagen de perfil si está disponible -->
                <?php if (!empty($user['imagen_perfil'])): ?>
                    <img src="assets/userimages/<?php echo htmlspecialchars($user['imagen_perfil']); ?>" alt="Imagen de perfil" class="img-fluid rounded-circle" style="max-width: 150px; max-height: 150px;">
                <?php else: ?>
                    <img src="assets/userimages/default.png" alt="Imagen de perfil predeterminada" class="img-fluid rounded-circle" style="max-width: 150px; max-height: 150px;">
                <?php endif; ?>
            </div>

            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">Información del Usuario</h4>
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <!-- Agrega más información del usuario según sea necesario -->
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="editar_perfil.php" class="btn btn-primary">Editar Perfil</a>
                    <a href="pedidos.php" class="btn btn-secondary">Mis Pedidos</a>
                    <a href="config/logout.php" class="btn btn-danger">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- PIE DE PÁGINA -->
    <?php include 'assets/includes/footer.php';?>
    <!-- /PIE DE PÁGINA -->
</body>
</html>
