<?php
// Inicializa variables para evitar errores si se accede directamente a la vista
$mensaje = $_GET['mensaje'] ?? null;
$tipoAlerta = $_GET['tipo'] ?? 'danger'; 
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <!-- Si styles.css está en la misma carpeta o raíz, ajusta la ruta si es necesario -->
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <a class="back mb-3 text-center" href="../../public/index.php">Biblioteca Pública</a>
        <h2 class="text-center mb-2">Recuperar Contraseña</h2>
        <p class="text-center text-muted mb-4">Ingresa tu correo para recuperar tu contraseña</p>

        <!-- Bloque PHP funcional gracias a la extensión .php -->
        <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo htmlspecialchars($tipoAlerta); ?> text-center">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
        <?php endif; ?>

        <!-- Ruta corregida hacia el controlador correspondiente -->
        <form method="POST" action="../Controllers/recuperar_contrasena.php">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" maxlength="100" placeholder="Ingresa tu correo" required>
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">Enviar enlace</button>
            </div>
            <p class="text-center mb-0"><a href="login.php">Volver al inicio de sesión</a></p>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
