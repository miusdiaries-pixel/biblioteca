<?php
require_once '../Controllers/validarinicio.php';
require_once '../Models/conexion.php';
SessionMiddleware::protegerVista();

$db = Conexion::conectar();
$idUsuario = $_SESSION['id_usuario'];

// Traer la información actual del usuario para rellenar los inputs
$stmt = $db->prepare("SELECT nombre, email, edad FROM persona WHERE id = :id");
$stmt->execute(['id' => $idUsuario]);
$usuario = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Mis Datos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

    <div class="card shadow p-4 bg-white animate-fade" style="max-width: 450px; width: 100%;">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">⚙️ Editar Mi Perfil</h4>
            <a href="dashboard_user.php" class="btn-close"></a>
        </div>
        <p class="text-muted small">Mantén tus credenciales actualizadas para recibir notificaciones del sistema.</p>
        <hr>

        <form method="POST" action="../Controllers/UsuarioController.php?accion=actualizar">
            <div class="mb-3">
                <label class="form-label small font-weight-bold">Nombre Completo</label>
                <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label small">Correo Electrónico</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label small">Edad</label>
                <input type="number" class="form-control" name="edad" value="<?php echo htmlspecialchars($usuario['edad']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label small text-danger">Nueva Contraseña (Dejar en blanco si no deseas cambiarla)</label>
                <input type="password" class="form-control" name="password" placeholder="••••••••">
            </div>

            <div class="d-flex gap-2 pt-2">
                <a href="dashboard_user.php" class="btn btn-secondary w-50">Cancelar</a>
                <button type="submit" class="btn btn-primary w-50">Guardar Cambios</button>
            </div>
        </form>
    </div>

</body>
</html>