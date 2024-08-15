<?php
include 'config/database.php';
?>

<?php include 'assets/includes/head.php';?>
</head>
<body>
    <!-- HEADER -->
    <?php include 'assets/includes/header.php';?>
    <!-- HEADER -->
<div class="section">
    <h2>Iniciar Sesión</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <form action="processlogin.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <div>
            <label for="username">Nombre de Usuario o Email:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Iniciar Sesión</button>
    </form>
</div>
    <?php include 'assets/includes/footer.php';?>
</body>
</html>
