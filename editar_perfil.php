<?php
include('config/database.php');
include('config/checksession.php');

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegúrate de que 'username' y 'email' estén en $_POST
    if (isset($_POST['username']) && isset($_POST['email'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        
        function sanitizeInput($input) {
            return htmlspecialchars(strip_tags($input));
        }

        $username = sanitizeInput($username);
        $email = sanitizeInput($email);
        
        try {
            $stmt = $conn->prepare("UPDATE clientes SET username = :username, email = :email WHERE id = :user_id");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            echo "Perfil actualizado con éxito.";
        } catch (PDOException $e) {
            error_log('Error en la actualización: ' . $e->getMessage());
            echo "Error al actualizar el perfil.";
        }
    } else {
        echo "Error: datos del formulario incompletos.";
    }
}

// Obtener los datos actuales del usuario para mostrarlos en el formulario
try {
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Error en la consulta: ' . $e->getMessage());
    echo "Error al obtener la información del usuario.";
}
?>


<?php include 'assets/includes/head.php';?>
    <style>
        /* Aquí va el código CSS proporcionado */
    </style>
</head>
<body>
    <!-- HEADER -->
    <?php include 'assets/includes/header.php';?>
    <!-- HEADER -->
    <div class="section">
        <h1>Editar Perfil</h1>
        <?php if (isset($message)) { ?>
            <div class="<?php echo $message['type'] === 'success' ? 'success-message' : 'error-message'; ?>">
                <?php echo htmlspecialchars($message['text']); ?>
            </div>
        <?php } ?>
        <form action="editar_perfil.php" method="POST">
            <label for="username">Nombre:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            
            <button type="submit">Actualizar Perfil</button>
        </form>
        <a href="userpanel.php">Volver al Panel de Usuario</a>
    </div>
    <!-- PIE DE PÁGINA -->
    <?php include 'assets/includes/footer.php';?>
    <!-- /PIE DE PÁGINA -->
</body>
</html>
