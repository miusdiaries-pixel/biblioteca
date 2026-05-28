<?php
require_once '../Controllers/validarinicio.php';
require_once '../Models/conexion.php';
SessionMiddleware::protegerVista();

// Asegurar que solo entren usuarios normales
if ($_SESSION['rol'] !== 'usuario') {
    header("Location: dashboard_admin.php");
    exit();
}

$db = Conexion::conectar();
$seccion = $_GET['seccion'] ?? 'catalogo';

// CONTROL DE SEGURIDAD: Si no se encuentra el ID en la sesión, lo recuperamos dinámicamente
if (!isset($_SESSION['id_usuario']) && isset($_SESSION['usuario'])) {
    $stmtUser = $db->prepare("SELECT id FROM persona WHERE nombre = :nombre LIMIT 1");
    $stmtUser->execute(['nombre' => $_SESSION['usuario']]);
    $resUser = $stmtUser->fetch();
    
    if ($resUser) {
        $_SESSION['id_usuario'] = $resUser['id'];
    } else {
        // Si no se encuentra coincidencia en la BD, destruimos la sesión por seguridad
        header("Location: ../../logout.php");
        exit();
    }
}

// Ahora es 100% seguro definir la variable sin lanzar Warnings
$idUsuario = $_SESSION['id_usuario'];

// 1. Obtener catálogo de libros disponibles
$libros = $db->query("SELECT * FROM libro")->fetchAll();

// 2. Obtener préstamos específicos de este usuario conectado
$stmt = $db->prepare("SELECT p.*, l.titulo_libro, l.autor FROM prestamo p JOIN libro l ON p.idLibro = l.id WHERE p.idUsuario = :idUsuario");
$stmt->execute(['idUsuario' => $idUsuario]);
$misPrestamos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary px-4 py-2 d-flex justify-content-between shadow-sm">
        <span class="navbar-brand mb-0 h1">📚 Biblioteca Digital</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white">¡Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>!</span>
            <a href="editar_usuario.php" class="btn btn-sm btn-light text-primary">⚙️ Mi Perfil</a>
            <a href="../../logout.php" class="btn btn-sm btn-danger">Cerrar sesión</a>
        </div>
    </nav>

    <div class="container my-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="list-group shadow-sm">
                    <a href="dashboard_user.php?seccion=catalogo" class="list-group-item list-group-item-action <?php echo $seccion === 'catalogo' ? 'active' : ''; ?>">📖 Catálogo de Libros</a>
                    <a href="dashboard_user.php?seccion=prestamos" class="list-group-item list-group-item-action <?php echo $seccion === 'prestamos' ? 'active' : ''; ?>">📋 Mis Préstamos</a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card shadow p-4 bg-white">

                    <?php if ($seccion === 'catalogo'): ?>
                        <h4 class="mb-3">Catálogo de Libros</h4>
                        <p class="text-muted small">Selecciona el ejemplar que desees leer y solicita su reserva.</p>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle small">
                                <thead class="table-dark">
                                    <tr><th>Título</th><th>Autor</th><th>Año</th><th>Acción</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($libros as $l): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($l['titulo_libro']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($l['autor']); ?></td>
                                            <td><?php echo htmlspecialchars($l['anio_publicacion']); ?></td>
                                            <td>
                                                <form method="POST" action="../Controllers/PrestamoController.php?accion=crear">
                                                    <input type="hidden" name="idLibro" value="<?php echo $l['id']; ?>">
                                                    <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?>">
                                                    <input type="hidden" name="fecha_limite" value="<?php echo date('Y-m-d H:i:s', strtotime('+7 days')); ?>">
                                                    <input type="hidden" name="detalles" value="Solicitado de manera online por el usuario.">
                                                    <input type="hidden" name="origen" value="usuario"> <button type="submit" class="btn btn-sm btn-success px-3">Solicitar Préstamo</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php elseif ($seccion === 'prestamos'): ?>
                        <h4 class="mb-3">Mis Préstamos Solicitados</h4>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle small">
                                <thead class="table-secondary">
                                    <tr><th>Libro</th><th>Autor</th><th>Fecha Límite Dev.</th><th>Estado</th></tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($misPrestamos)): ?>
                                        <tr><td colspan="4" class="text-center text-muted py-3">Aún no has solicitado ningún libro.</td></tr>
                                    <?php endif; ?>
                                    <?php foreach ($misPrestamos as $p): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($p['titulo_libro']); ?></td>
                                            <td><?php echo htmlspecialchars($p['autor']); ?></td>
                                            <td><?php echo htmlspecialchars($p['fecha_limite']); ?></td>
                                            <td>
                                                <span class="badge <?php echo $p['estado'] === 'pendiente' ? 'bg-warning text-dark' : 'bg-success'; ?>">
                                                    <?php echo htmlspecialchars($p['estado']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

</body>
</html>