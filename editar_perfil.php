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
                    // Mensaje de éxito
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

        header("Location: editar_perfil.php?verificado=actualizado");
        exit();
    } else {
        header("Location: editar_perfil.php?error=imcompletado");
        exit();
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
        /* Estilo personalizado para el perfil */
        .profile-container {
            text-align: center;
            margin-top: 20px;
        }

        .profile-image-container {
            position: relative;
            display: inline-block;
        }

        .profile-image-container img {
            border-radius: 50%;
            max-width: 150px;
            max-height: 150px;
        }

        .profile-image-container .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            border-radius: 50%;
            transition: opacity 0.3s ease;
            text-align: center;
            cursor: pointer; /* Cambia el cursor para indicar que es clickeable */
        }

        .profile-image-container:hover .overlay {
            opacity: 1;
        }

        .file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0; /* Oculta el input file */
            cursor: pointer; /* Cambia el cursor para indicar que es clickeable */
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .btn-submit {
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .profile-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <?php include 'assets/includes/header.php';?>
    <!-- HEADER -->
    <div class="container profile-container">
        <h1>Editar Perfil</h1>
        <?php if (isset($message)) { ?>
            <div class="<?php echo $message['type'] === 'success' ? 'alert alert-success' : 'alert alert-danger'; ?>">
                <?php echo htmlspecialchars($message['text']); ?>
            </div>
        <?php } ?>
        <form action="editar_perfil.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Nombre:</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group profile-image-container">
                <!-- Mostrar imagen de perfil si está disponible -->
                <img id="profileImage" src="assets/userimages/<?php echo !empty($user['imagen_perfil']) ? htmlspecialchars($user['imagen_perfil']) : 'default.png'; ?>" alt="Imagen de perfil" class="img-fluid rounded-circle">
                <div class="overlay">Subir nueva imagen</div>
                <input type="file" id="imagen_perfil" name="imagen_perfil" class="file-input" onchange="previewImage(event)">
            </div>

            <button type="submit" class="btn btn-primary btn-submit">Actualizar Perfil</button>
        </form>

        <a href="userpanel.php" class="btn btn-secondary">Volver al Panel de Usuario</a>
    </div>
    <!-- PIE DE PÁGINA -->
    <?php include 'assets/includes/footer.php';?>
    <!-- /PIE DE PÁGINA -->

    <script>
        function previewImage(event) {
            const input = event.target;
            const file = input.files[0];
            const img = document.getElementById('profileImage');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    img.src = e.target.result;
                };

                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>