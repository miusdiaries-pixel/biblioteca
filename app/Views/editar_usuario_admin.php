<?php
require_once '../Controllers/validarinicio.php';
require_once '../Models/conexion.php';
SessionMiddleware::protegerVista();

if ($_SESSION['rol'] !== 'admin') {
    header("Location: dashboard_user.php");
    exit();
}

$db = Conexion::conectar();
$id = intval($_GET['id'] ?? 0);

$stmt = $db->prepare("SELECT id, nombre, email, edad, rol FROM persona WHERE id = :id");
$stmt->execute(['id' => $id]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$u) {
    header("Location: dashboard_admin.php?seccion=usuarios");
    exit();
}

$esMismoCuenta = ($u['id'] == $_SESSION['id_usuario']);

$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow p-4 bg-white" style="max-width:450px;width:100%;">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Editar Usuario</h4>
            <a href="dashboard_admin.php?seccion=usuarios" class="btn-close"></a>
        </div>
        <hr>
        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger py-2 small">
                <?php foreach ($errores as $e): ?>
                    <div><?php echo htmlspecialchars($e); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="../Controllers/UsuarioController.php?accion=actualizar_admin&id=<?php echo $u['id']; ?>">
            <div class="mb-3">
                <label class="form-label small">Nombre</label>
                <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($u['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label small">Correo</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($u['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label small">Edad</label>
                <input type="number" class="form-control" name="edad" value="<?php echo htmlspecialchars($u['edad']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label small">Rol</label>
                <?php if ($esMismoCuenta): ?>
                    <input type="text" class="form-control" value="admin" disabled>
                    <input type="hidden" name="rol" value="admin">
                    <div class="form-text text-muted">No puedes cambiar tu propio rol.</div>
                <?php else: ?>
                    <select class="form-select" name="rol">
                        <option value="usuario" <?php echo $u['rol']==='usuario'?'selected':''; ?>>Usuario</option>
                        <option value="admin"   <?php echo $u['rol']==='admin'  ?'selected':''; ?>>Admin</option>
                    </select>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label small">Nueva Contraseña <span class="text-muted">(dejar en blanco para no cambiar)</span></label>
                <input type="password" class="form-control" name="password" placeholder="Min. 6 caracteres, mayúscula, número y símbolo">
            </div>
            <div class="d-flex gap-2 pt-2">
                <a href="dashboard_admin.php?seccion=usuarios" class="btn btn-secondary w-50">Cancelar</a>
                <button type="submit" class="btn btn-primary w-50">Guardar</button>
            </div>
        </form>
    </div>
</body>
</html>
