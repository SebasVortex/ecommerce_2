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
   .row-us{
        display: flex;
        align-items: center;
        margin: 60px 0 ;
    }
    .d-flex{
        display: flex;
        justify-content: flex-start; 
        padding-left: 60px; 
    }
    .btn-space {
        margin-right: 30px; 
        border: none;
        transition: background-color 0.3s, box-shadow 0.2s; 
        box-shadow: 0 7px 11px #a0a0a0;  
        border-radius: 35px;
        width: 17%;
        height: 40px;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        padding: 9px 12px;
    }
    .btn-primary{
        background-color: #655690;
        color: #E7DAFF;
    }
    .btn-primary:hover {
        background-color: #B31920;
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }
    .btn-secondary{
        background-color: #337AB7;
        color: #D8EDFF;
    }
    .btn-danger{
        background-color: #D9534F;
        color: #FFF;
    }
    a.btn {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    }
    .card-body p{
        font-size: 18px;
    }
    .title{
        margin: 40px;
    }
    @media (max-width: 992px){
        .btn-space{
            width: 25%;
        }
    }
    @media (max-width: 768px) {
    .row-us {
        flex-direction: column;
        align-items: center;
        margin: 50px 0;
        gap: 50px;
    }

    .d-flex {
        flex-direction: column;
        align-items: center;
        padding-left: 0;
    }

    .btn-space {
        width: 65%; 
        margin-bottom: 15px;
        margin-right: 0; 
    }

    .title {
        margin: 20px 0;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .row-us {
        margin: 50px 0;
        gap: 50px;
    }

    .profile-image-container img {
        width: 150px;
        height: 150px; 
    }

    .btn-space {
        width: 100%; 
        font-size: 16px; 
    }

}
</style>
</head>
<body>
    <!-- HEADER -->
    <?php include 'assets/includes/header.php';?>
    <!-- HEADER -->
    <div class="container mt-5">
        <h1 class="mb-4 title"> Hola, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        
        <div class="row-us">
            <div class="col-md-4 text-center">
                <!-- Mostrar imagen de perfil si está disponible -->
                <?php if (!empty($user['imagen_perfil'])): ?>
                    <img src="assets/userimages/<?php echo htmlspecialchars($user['imagen_perfil']); ?>" alt="Imagen de perfil" class="img-fluid rounded-circle" style="position: relative;display: inline-block;width: 200px;height: 200px;border-radius: 50%;overflow: hidden;">
                <?php else: ?>
                    <img src="assets/userimages/default.png" alt="Imagen de perfil predeterminada" class="img-fluid rounded-circle" style="position: relative; display: inline-block;width: 200px;height: 200px;border-radius: 50%;overflow: hidden;">
                <?php endif; ?>
            </div>

            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                        <p><strong>Usuario:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                        <!-- Agrega más información del usuario según sea necesario -->
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <a href="editar_perfil.php" class="btn btn-space btn-primary">Editar Perfil <span class="material-symbols-outlined">person_edit</span></a>
            <a href="pedidos.php" class="btn btn-space btn-secondary">Mis Pedidos <span class="material-symbols-outlined">shopping_cart</span></a>
            <a href="config/logout.php" class="btn btn-space btn-danger">Cerrar Sesión <span class="material-symbols-outlined">power_settings_new</span></a>
        </div>
    </div>

    <!-- PIE DE PÁGINA -->
    <?php include 'assets/includes/footer.php';?>
    <!-- /PIE DE PÁGINA -->
</body>
</html>
