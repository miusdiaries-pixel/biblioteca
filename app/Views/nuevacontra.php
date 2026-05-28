<?php
// 1. Inicialización de variables para evitar errores por datos indefinidos
$mensaje = $_GET['mensaje'] ?? null;
$tipoAlerta = $_GET['tipo'] ?? 'danger';

// Si viene un token en la URL, asumimos inicialmente que es apto para evaluación
$token = $_GET['token'] ?? '';
$tokenValido = !empty($token); 
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
                <a class="back mb-3 text-center" href="../../public/index.php">Biblioteca Pública</a>
        <h2 class="text-center mb-4">Nueva Contraseña</h2>

        <!-- Renderizado dinámico de alertas -->
        <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo htmlspecialchars($tipoAlerta); ?> text-center">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
        <?php endif; ?>

        <!-- Validación visual del token -->
        <?php if ($tokenValido): ?>
        <!-- Ruta corregida apuntando de forma exacta al controlador -->
        <form method="POST" action="../Controllers/nueva_contrasena.php?token=<?php echo htmlspecialchars($token); ?>">
            <div class="mb-3">
                <label for="password" class="form-label">Nueva contraseña</label>
                <input type="password" class="form-control" id="password" name="password" minlength="6" maxlength="255" placeholder="Mínimo 6 caracteres" required>
                <div class="form-text">Debe tener mayúscula, minúscula, número y símbolo.</div>
            </div>
            <div class="mb-3">
                <label for="password_confirm" class="form-label">Confirmar contraseña</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" minlength="6" maxlength="255" placeholder="Repite la contraseña" required>
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">Guardar contraseña</button>
            </div>
        </form>
        <?php else: ?>
        <div class="alert alert-warning text-center">
            El enlace de recuperación es inválido o ha expirado.
        </div>
        <div class="d-grid">
            <a href="login.html" class="btn btn-primary">Ir al inicio de sesión</a>
        </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
