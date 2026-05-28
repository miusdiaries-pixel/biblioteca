<?php
// 1. Iniciamos sesión obligatoriamente en la parte superior para leer los errores
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Extraemos el arreglo de errores guardado por el RegisterController
$errores = $_SESSION['errores'] ?? ["Ocurrió un error inesperado al procesar tu registro."];

// 3. Limpiamos la sesión para que no se dupliquen los mensajes al recargar
unset($_SESSION['errores']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Registro</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 500px; width: 100%;">
                <a class="back mb-3 text-center" href="../../public/index.php">Biblioteca Pública</a>
        <h2 class="text-center mb-4">No se pudo registrar</h2>
        
        <!-- 4. El bucle foreach ahora sí tiene datos reales que recorrer -->
        <?php foreach ($errores as $error): ?>
            <div class="alert alert-danger py-2">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endforeach; ?>
        
        <a href="registro.html" class="btn btn-primary d-block mt-3">Volver</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
