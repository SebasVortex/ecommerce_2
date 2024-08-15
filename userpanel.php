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
</head>
<body>
    <!-- HEADER -->
    <?php include 'assets/includes/header.php';?>
    <!-- HEADER -->
    <h1>Bienvenido, <?php echo htmlspecialchars($user['username']); ?></h1>
    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <!-- Agrega más información del usuario según sea necesario -->

    <!-- Opciones de usuario -->
    <a href="editar_perfil.php">Editar Perfil</a>
    <a href="mis_pedidos.php">Mis Pedidos</a>
    <a href="config/logout.php">Cerrar Sesión</a>
        <!-- PIE DE PÁGINA -->
        <?php include 'assets/includes/footer.php';?>
    <!-- /PIE DE PÁGINA -->
</body>
</html>
