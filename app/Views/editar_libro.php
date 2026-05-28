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

$stmt = $db->prepare("SELECT * FROM libro WHERE id = :id");
$stmt->execute(['id' => $id]);
$libro = $stmt->fetch();

if (!$libro) {
    header("Location: dashboard_admin.php?seccion=libros");
    exit();
}

$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow p-4 bg-white" style="max-width:450px;width:100%;">
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Editar Libro</h4>
            <a href="dashboard_admin.php?seccion=libros" class="btn-close"></a>
        </div>
        <hr>
        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger py-2 small">
                <?php foreach ($errores as $e): ?>
                    <div><?php echo htmlspecialchars($e); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="../Controllers/LibroController.php?accion=editar&id=<?php echo $libro['id']; ?>">
            <div class="mb-3">
                <label class="form-label small">Título del Libro</label>
                <input type="text" class="form-control" name="titulo_libro" value="<?php echo htmlspecialchars($libro['titulo_libro']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label small">Autor</label>
                <input type="text" class="form-control" name="autor" value="<?php echo htmlspecialchars($libro['autor']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label small">Año de Publicación</label>
                <input type="number" class="form-control" name="anio_publicacion" value="<?php echo htmlspecialchars($libro['anio_publicacion']); ?>" required>
            </div>
            <div class="d-flex gap-2 pt-2">
                <a href="dashboard_admin.php?seccion=libros" class="btn btn-secondary w-50">Cancelar</a>
                <button type="submit" class="btn btn-primary w-50">Guardar Cambios</button>
            </div>
        </form>
    </div>
</body>
</html>
