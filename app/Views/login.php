<?php
// Iniciamos sesión en la parte superior para verificar si viene de un registro exitoso
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$registroExito = $_SESSION['registro_exito'] ?? null;

// Borramos el mensaje de inmediato para que desaparezca al recargar la página
unset($_SESSION['registro_exito']);
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar Sesión</title>
        <link rel="stylesheet" href="styles.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
        <a class="back mb-3 text-center" href="../../public/index.php">Biblioteca Pública</a>
        <h2 class="text-center mb-4">Iniciar Sesión</h2>

            <!-- MENSAJE DISCRETO: Si el registro fue exitoso, muestra esta alerta verde -->
            <?php if ($registroExito): ?>
            <div class="alert alert-success alert-dismissible fade show py-2 small" role="alert">
                <?php echo htmlspecialchars($registroExito); ?>
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <form method="POST" action="../Controllers/login.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" maxlength="100" placeholder="Ingresa tu correo" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" maxlength="255" placeholder="Ingresa tu contraseña" required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </div>
                <p class="text-center mb-1">¿No tienes cuenta? <a href="registro.php">Registrarme</a></p>
                <p class="text-center mb-0"><a href="recuperarcontra.php">¿Olvidaste tu contraseña?</a></p>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>