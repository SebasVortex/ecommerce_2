<?php
include('config/database.php');
include('config/checksession.php');

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegúrate de que 'username', 'email', y 'imagen_perfil' estén en $_POST
    if (isset($_POST['username']) && isset($_POST['email'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $imagen_perfil = $_FILES['imagen_perfil']['name'] ?? ''; // Obtener el nombre del archivo

        function sanitizeInput($input) {
            return htmlspecialchars(strip_tags($input));
        }

        $username = sanitizeInput($username);
        $email = sanitizeInput($email);

        // Manejar la imagen de perfil
        if ($imagen_perfil) {
            $target_dir = "assets/userimages/"; // Carpeta para subir la imagen
            $target_file = $target_dir . basename($imagen_perfil);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Verificar si la imagen es realmente una imagen
            $check = getimagesize($_FILES["imagen_perfil"]["tmp_name"]);
            if ($check === false) {
                echo "El archivo no es una imagen.";
                $uploadOk = 0;
            }

            // Verificar el tamaño del archivo
            if ($_FILES["imagen_perfil"]["size"] > 3000000) { // Tamaño máximo: 3MB (3 * 1024 * 1024 bytes)
                echo "El archivo es demasiado grande.";
                $uploadOk = 0;
            }

            // Permitir ciertos formatos de archivo
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
                $uploadOk = 0;
            }

            // Verificar si $uploadOk es 0 debido a un error
            if ($uploadOk == 0) {
                echo "La imagen no se subió.";
            } else {
                if (move_uploaded_file($_FILES["imagen_perfil"]["tmp_name"], $target_file)) {
                    echo "La imagen ". htmlspecialchars(basename($_FILES["imagen_perfil"]["name"])). " ha sido subida.";

                    // Actualizar el nombre de la imagen en la base de datos
                    $stmt = $conn->prepare("UPDATE clientes SET username = :username, email = :email, imagen_perfil = :imagen_perfil WHERE id = :user_id");
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':imagen_perfil', $imagen_perfil);
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    echo "Hubo un error al subir la imagen.";
                }
            }
        } else {
            // Si no se subió una nueva imagen, solo actualizar el nombre y el correo
            $stmt = $conn->prepare("UPDATE clientes SET username = :username, email = :email WHERE id = :user_id");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
        }

        echo "Perfil actualizado con éxito.";
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
        <form action="editar_perfil.php" method="POST" enctype="multipart/form-data">
            <label for="username">Nombre:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            
            <label for="imagen_perfil">Foto de Perfil:</label>
            <input type="file" id="imagen_perfil" name="imagen_perfil">
            
            <button type="submit">Actualizar Perfil</button>
        </form>

        <a href="userpanel.php">Volver al Panel de Usuario</a>
    </div>
    <!-- PIE DE PÁGINA -->
    <?php include 'assets/includes/footer.php';?>
    <!-- /PIE DE PÁGINA -->
</body>
</html>
