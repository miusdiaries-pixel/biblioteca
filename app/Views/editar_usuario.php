<?php
require_once '../Controllers/validarinicio.php';
require_once '../Models/conexion.php';
SessionMiddleware::protegerVista();

$db = Conexion::conectar();

if (empty($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['id_usuario'];

$stmt = $db->prepare("SELECT nombre, email, edad FROM persona WHERE id = :id");
$stmt->execute(['id' => $idUsuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: login.php");
    exit();
}

$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);

$volver = $_SESSION['rol'] === 'admin' ? 'dashboard_admin.php' : 'dashboard_user.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow p-4 bg-white" style="max-width:450px;width:100%;">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Editar Mi Perfil</h4>
            <a href="<?php echo $volver; ?>" class="btn-close"></a>
        </div>
        <hr>
        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger py-2 small">
                <?php foreach ($errores as $e): ?>
                    <div><?php echo htmlspecialchars($e); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="../Controllers/UsuarioController.php?accion=actualizar">
            <div class="mb-3">
                <label class="form-label small">Nombre Completo</label>
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
                <label class="form-label small">Nueva Contraseña <span class="text-muted">(dejar en blanco para no cambiar)</span></label>
                <input type="password" class="form-control" name="password" placeholder="Min. 6 caracteres, mayúscula, número y símbolo">
            </div>
            <div class="d-flex gap-2 pt-2">
                <a href="<?php echo $volver; ?>" class="btn btn-secondary w-50">Cancelar</a>
                <button type="submit" class="btn btn-primary w-50">Guardar</button>
            </div>
        </form>
    </div>
</body>
</html>
