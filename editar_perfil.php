<?php
include('config/database.php');
include('config/checksession.php');

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['nombre'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $nombre = trim($_POST['nombre']);
        $imagen_perfil = $_FILES['imagen_perfil']['name'] ?? '';

        function sanitizeInput($input) {
            return htmlspecialchars(strip_tags($input));
        }

        $username = sanitizeInput($username);
        $email = sanitizeInput($email);
        $nombre = sanitizeInput($nombre);

        $uploadOk = 1;

        if ($imagen_perfil) {
            $target_dir = "assets/userimages/";
            $target_file = $target_dir . basename($imagen_perfil);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["imagen_perfil"]["tmp_name"]);
            if ($check === false) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'El archivo no es una imagen.'];
                $uploadOk = 0;
            }

            if ($_FILES["imagen_perfil"]["size"] > 3000000) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'El archivo es demasiado grande.'];
                $uploadOk = 0;
            }

            if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                $_SESSION['message'] = ['type' => 'danger', 'text' => 'Solo se permiten archivos JPG, JPEG, PNG y GIF.'];
                $uploadOk = 0;
            }

            if ($uploadOk) {
                if (move_uploaded_file($_FILES["imagen_perfil"]["tmp_name"], $target_file)) {
                    $stmt = $conn->prepare("UPDATE clientes SET username = :username, email = :email, imagen_perfil = :imagen_perfil WHERE id = :user_id");
                    $stmt->bindParam(':imagen_perfil', $imagen_perfil);
                } else {
                    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Hubo un error al subir la imagen.'];
                }
            }
        } else {
            $stmt = $conn->prepare("UPDATE clientes SET username = :username, email = :email, nombre = :nombre WHERE id = :user_id");
        }

        if (isset($stmt)) {
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['message'] = ['type' => 'success', 'text' => 'Perfil actualizado con éxito.'];
            header("Location: editar_perfil.php");
            exit();
        } else {
            $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error al actualizar el perfil.'];
            header("Location: editar_perfil.php");
            exit();
        }
    }
}

try {
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Error en la consulta: ' . $e->getMessage());
    $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error al obtener la información del usuario.'];
}
?>

<?php include 'assets/includes/head.php';?>
<style>
    /* Estilo personalizado para el perfil */
    .profile-container {
        text-align: center;
        margin-top: 20px;
    }
    .alert {
        padding: 15px;
        margin: 10px 0;
        border-radius: 5px;
        font-size: 16px;
        display: inline-block;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .profile-image-container {
        position: relative;
        display: inline-block;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        overflow: hidden;
    }

    .profile-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
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
        cursor: pointer;
    }

    .profile-image-container:hover .overlay {
        opacity: 1;
    }

    .form-ent {
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
        cursor: pointer;
    }

    .password-container {
        display: flex;
        align-items: center;
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
        width: 24%;
        margin: 20px 0;
        height: 45px;
        font-size: 18px;
        font-weight: 600;
    }

    .btn-log:hover {
        background-color: #B31920;
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
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

    .psw-cnt{
        width: 116.5%;
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

    .modal-header {
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

    .form-modal label {
        font-weight: 500;
    }

    .modal-title {
        font-size: 32px;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .form-ent {
            flex-direction: column;
            align-items: center;
        }

        .profile-image-container {
            margin-bottom: 20px;
        }

        .form-group {
            width: 100%; 
        }

        .btn-log {
            width: 50%; 
            margin-top: 20px;
        }

        .psw-cnt {
            width: 120.66%;
        }
    }

    @media (max-width: 480px) {
        .profile-image-container img {
            width: 150px;
            height: 150px;
        }

        .form-group label {
            font-size: 14px; 
        }

        .btn-log {
            padding: 10px;
            font-size: 16px; 
        }
    }

    .alert {
        padding: 15px;
        margin: 10px 0;
        border-radius: 5px;
        font-size: 16px;
        display: inline-block;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>
</head>
<body>
    <!-- HEADER -->
    <?php include 'assets/includes/header.php';?>
    <!-- HEADER -->
    <div class="container profile-container">
        <h1>Editar Perfil</h1>
        <?php if (isset($_SESSION['message'])) { ?>
            <div class="alert <?php echo $_SESSION['message']['type'] === 'success' ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo htmlspecialchars($_SESSION['message']['text']); ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php } ?>
        <form action="editar_perfil.php" method="POST" enctype="multipart/form-data">
            <div class="form-ent">
                <div class="profile-image-container">
                    <!-- Mostrar imagen de perfil si está disponible -->
                    <img id="profileImage" src="assets/userimages/<?php echo !empty($user['imagen_perfil']) ? htmlspecialchars($user['imagen_perfil']) : 'default.png'; ?>" alt="Imagen de perfil" class="img-fluid rounded-circle">
                    <div class="overlay">Subir nueva imagen</div>
                    <input type="file" id="imagen_perfil" name="imagen_perfil" class="file-input" onchange="previewImage(event)">
                </div> 
                <div class="group-junt">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Usuario:</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <div class="password-container psw-cnt">
                            <input type="password" id="password" name="password" class="form-control" value="<?php echo htmlspecialchars($user['password']); ?>" readonly>
                            <span class="show-password" onclick="togglePassword('password', 'passwordIcon')"><img id="passwordIcon" src="assets/images/lock.png" alt="Toggle Password"></span>
                        </div>
                        <div class="psw-btn">
                            <button type="button" class="btn-secondary change-pssw" data-toggle="modal" data-target="#changePasswordModal">Cambiar Contraseña</button><img id="changepasswordIcon" src="assets/images/new-password.png" alt="Change Password">
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
                                <span class="show-password" onclick="togglePassword('current_password', 'currentPasswordIcon')"><img id="currentPasswordIcon" src="assets/images/lock.png" alt="Toggle Password"></span>
                            </div>
                        </div>
                        <div class="form-modal">
                            <label for="new_password">Nueva Contraseña:</label>
                            <div class="password-container">
                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                                <span class="show-password" onclick="togglePassword('new_password', 'newPasswordIcon')"><img id="newPasswordIcon" src="assets/images/lock.png" alt="Toggle Password"></span>
                            </div>
                        </div>
                        <div class="form-modal">
                            <label for="confirm_password">Confirmar Contraseña:</label>
                            <div class="password-container">
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                <span class="show-password" onclick="togglePassword('confirm_password', 'confirmPasswordIcon')"><img id="confirmPasswordIcon" src="assets/images/lock.png" alt="Toggle Password"></span>
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

        function togglePassword(fieldId, iconId) {
            var passwordField = document.getElementById(fieldId);
            var passwordIcon = document.getElementById(iconId);
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
