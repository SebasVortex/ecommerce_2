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
        width: 200px; /* Fijar ancho */
        height: 200px; /* Fijar altura */
        border-radius: 50%; /* Redondear el contenedor */
        overflow: hidden; /* Ocultar partes desbordantes de la imagen */
    }

    .profile-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* La imagen se recorta para llenar el contenedor */
        border-radius: 50%; /* Redondear la imagen */
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
        transition: opacity 0.3s ease;
        text-align: center;
        cursor: pointer;
    }

    .profile-image-container:hover .overlay {
        opacity: 1;
    }

    .form-ent{
        display: flex;
        justify-content: space-evenly;
        align-items: center;
        margin: 50px 0;
    }

    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    
    .form-group {
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
    align-items: flex-start; 
    width: 125%;
    }   
    .password-container span img {
        width: 35px;
        margin-left: 15px;
    }
    .password-container {
        width: 116%;
        display: flex;
        align-items: flex-end;
    }
    .form-group label {
        display: block;
        font-weight: 500;
        margin-left: 2px;
    }
    .btn-log {
        background-color: #D10024;
        transition: background-color 0.3s, box-shadow 0.2s; 
        box-shadow: 0 7px 11px #a0a0a0;
        border: none;
        color: #fff;
        border-radius: 35px;
        width: 20%;
        margin: 20px 0;
        height: 45px;
        font-size: 18px;
        font-weight: 600;
    }
    .btn-log:hover {
        background-color: #B31920; /* Un verde ligeramente más oscuro para el hover */
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2); /* Sombra para dar sensación de elevación */
    }

    /* Estilo para cuando el botón está enfocado o activo */
    .btn-log:focus, .btn-log:active {
        outline: none; /* Elimina el outline que algunos navegadores añaden */
        background-color: #8E171C; /* Un verde aún más oscuro para el focus/active */
    }
    .show-password {
        cursor: pointer;
    }
    .change-pssw {
        background-color: transparent;
        border: none;
        color: #D10024;
        padding: 0;
    }
    .form-group input {
    box-shadow: 4px 4px 10px #bcbcbca6;
    border-radius: 10.85px;
    background-color: #eee;
    border: none;
    }
    img#changepasswordIcon {
        margin-left: 5px;
        width: 20px;
    }
    .psw-btn {
        margin-top: 10px;
        margin-left: 4px;
    }
    .modal-header{
        text-align: center;
    }
    form#changePasswordForm {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    }
    .form-modal input {
    box-shadow: 4px 4px 10px #bcbcbca6;
    border-radius: 10.85px;
    background-color: #eee;
    border: none;
    }
    .form-modal {
        width: 70%;
    }
    .form-modal label{
        font-weight: 500;
    }
    .modal-title {
        font-size: 32px;
        font-weight: 500;
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
            <div class="form-ent">
                <div class="profile-image-container">
                    <!-- Mostrar imagen de perfil si está disponible -->
                    <img id="profileImage" src="assets/userimages/<?php echo !empty($user['imagen_perfil']) ? htmlspecialchars($user['imagen_perfil']) : 'default.png'; ?>" alt="Imagen de perfil" class="img-fluid rounded-circle">
                    <div class="overlay">Subir nueva imagen</div>
                    <input type="file" id="imagen_perfil" name="imagen_perfil" class="file-input" onchange="previewImage(event)">
                    <div class="max-size"><p>Tamaño Máximo: <b>5MB</b></p></div>
                </div> 
                <div class="group-junt">
                    <div class="form-group">
                        <label for="username">Nombre:</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" class="form-control" value="<?php echo htmlspecialchars($user['password']); ?>" readonly>
                            <span class="show-password" onclick="togglePassword()"><img id="passwordIcon" src="assets/images/lock.png" alt="Toggle Password"></span>
                        </div>
                        <div class="psw-btn">
                            <button type="button" class="btn-secondary change-pssw" data-toggle="modal" data-target="#changePasswordModal">Cambiar Contraseña</button><img id="changepasswordIcon" src="assets/images/new-password.png" alt="Change Password"></span>
                        </div>
                    </div>
                </div>                
            </div>
            <button type="submit" class="btn btn-primary btn-submit btn-log">Actualizar Perfil</button>
        </form>

        <a href="userpanel.php" class="btn btn-secondary">Volver al Panel de Usuario</a>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="changePasswordModalLabel">Cambiar Contraseña</h5>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm" method="POST" action="config/cambiar_contrasena.php">
                        <div class="form-modal">
                            <label for="current_password">Contraseña Actual:</label>
                            <div class="password-container">
                                <input type="password" id="current_password" name="current_password" class="form-control" required>
                                <span class="show-password" onclick="togglePassword()"><img id="passwordIcon" src="assets/images/lock.png" alt="Toggle Password"></span>
                            </div>
                        </div>
                        <div class="form-modal">
                            <label for="new_password">Nueva Contraseña:</label>
                            <div class="password-container">
                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                                <span class="show-password" onclick="togglePassword()"><img id="passwordIcon" src="assets/images/lock.png" alt="Toggle Password"></span>
                            </div>
                        </div>
                        <div class="form-modal">
                            <label for="confirm_password">Confirmar Contraseña:</label>
                            <div class="password-container">
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                <span class="show-password" onclick="togglePassword()"><img id="passwordIcon" src="assets/images/lock.png" alt="Toggle Password"></span>
                            </div>
                        </div>
                        <button type="submit" class="btn-log" style="width: 40%;">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
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
        function togglePassword() {
            var passwordField = document.getElementById('password');
            var passwordIcon = document.getElementById('passwordIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.src = 'assets/images/unlock.png';
            } else {
                passwordField.type = 'password';
                passwordIcon.src = 'assets/images/lock.png';
            }
        }
    </script>
</body>
</html>