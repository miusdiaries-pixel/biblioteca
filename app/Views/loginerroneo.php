<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$mensajeError = $_SESSION['login_error'] ?? "El correo electrónico o la contraseña son incorrectos.";
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Login</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <a class="back mb-3 text-center" href="../../public/index.php">Biblioteca Pública</a>
        <h2 class="text-center mb-4">Error de Login</h2>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($mensajeError); ?>
        </div>
        <a href="login.php" class="btn btn-primary d-block">Volver</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
