<?php
require_once '../Controllers/validarinicio.php';
require_once '../Models/conexion.php';
SessionMiddleware::protegerVista();

if ($_SESSION['rol'] !== 'admin') {
    header("Location: dashboard_user.php");
    exit();
}

$db = Conexion::conectar();
$seccion = $_GET['seccion'] ?? 'usuarios';

// Consultas básicas de lectura para alimentar las tablas y desplegables
$usuarios = $db->query("SELECT id, nombre, email, edad, rol FROM persona")->fetchAll();
$libros = $db->query("SELECT * FROM libro")->fetchAll();
$prestamos = $db->query("SELECT p.*, per.nombre as usuario, l.titulo_libro as libro FROM prestamo p JOIN persona per ON p.idUsuario = per.id JOIN libro l ON p.idLibro = l.id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-dark px-4 py-2 d-flex justify-content-between">
        <span class="navbar-brand mb-0 h1">Panel Administrativo</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white">Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong></span>
            <a href="dashboard_user.php" class="btn btn-sm btn-secondary">Editar Perfil</a>
            <a href="../../logout.php" class="btn btn-sm btn-danger">Cerrar sesión</a>
        </div>
    </nav>

    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-md-2 mb-3">
                <div class="list-group shadow-sm">
                    <a href="dashboard_admin.php?seccion=usuarios" class="list-group-item list-group-item-action <?php echo $seccion === 'usuarios' ? 'active' : ''; ?>">👥 Usuarios</a>
                    <a href="dashboard_admin.php?seccion=libros" class="list-group-item list-group-item-action <?php echo $seccion === 'libros' ? 'active' : ''; ?>">📚 Libros</a>
                    <a href="dashboard_admin.php?seccion=prestamos" class="list-group-item list-group-item-action <?php echo $seccion === 'prestamos' ? 'active' : ''; ?>">📋 Préstamos</a>
                </div>
            </div>

            <div class="col-md-10">
                <div class="card shadow p-4">

                    <?php if ($seccion === 'usuarios'): ?>
                        <div class="row">
                            <div class="col-lg-4 mb-4">
                                <h5>Registrar Nuevo Usuario</h5>
                                <form method="POST" action="../Controllers/registro.php">
                                    <input type="hidden" name="origen" value="admin">
                                    <div class="mb-2"><label class="small">Nombre</label><input type="text" class="form-control form-control-sm" name="nombre" required></div>
                                    <div class="mb-2"><label class="small">Correo</label><input type="email" class="form-control form-control-sm" name="email" required></div>
                                    <div class="mb-2"><label class="small">Edad</label><input type="number" class="form-control form-control-sm" name="edad" required></div>
                                    <div class="mb-2"><label class="small">Contraseña</label><input type="password" class="form-control form-control-sm" name="password" required></div>
                                    <div class="mb-2"><label class="small">Rol</label>
                                        <select class="form-select form-select-sm" name="rol" required>
                                            <option value="usuario">Usuario</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">Crear Usuario</button>
                                </form>
                            </div>
                            <div class="col-lg-8">
                                <h5>Listado de Personal y Lectores</h5>
                                <table class="table table-sm table-striped small align-middle">
                                    <thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Edad</th><th>Rol</th></tr></thead>
                                    <tbody>
                                        <?php foreach ($usuarios as $u): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($u['id']); ?></td>
                                                <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                                <td><?php echo htmlspecialchars($u['edad']); ?></td>
                                                <td><span class="badge <?php echo $u['rol'] === 'admin' ? 'bg-dark' : 'bg-secondary'; ?>"><?php echo htmlspecialchars($u['rol']); ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <?php elseif ($seccion === 'libros'): ?>
                        <div class="row">
                            <div class="col-lg-4 mb-4">
                                <h5>Añadir Nuevo Libro</h5>
                                <form method="POST" action="../Controllers/LibroController.php?accion=crear">
                                    <div class="mb-2"><label class="small">Título del Libro</label><input type="text" class="form-control form-control-sm" name="titulo_libro" required></div>
                                    <div class="mb-2"><label class="small">Autor</label><input type="text" class="form-control form-control-sm" name="autor" required></div>
                                    <div class="mb-2"><label class="small">Año Publicación</label><input type="number" class="form-control form-control-sm" name="anio_publicacion" required></div>
                                    <button type="submit" class="btn btn-success btn-sm w-100 mt-2">Guardar Libro</button>
                                </form>
                            </div>
                            <div class="col-lg-8">
                                <h5>Inventario de Libros</h5>
                                <table class="table table-sm table-striped small align-middle">
                                    <thead><tr><th>ID</th><th>Título</th><th>Autor</th><th>Año</th><th>Acciones</th></tr></thead>
                                    <tbody>
                                        <?php foreach ($libros as $l): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($l['id']); ?></td>
                                                <td><?php echo htmlspecialchars($l['titulo_libro']); ?></td>
                                                <td><?php echo htmlspecialchars($l['autor']); ?></td>
                                                <td><?php echo htmlspecialchars($l['anio_publicacion']); ?></td>
                                                <td><a href="editar_libro.php?id=<?php echo $l['id']; ?>" class="btn btn-primary btn-xs py-0 px-1">Editar</a></td>
                                                <td><a href="../Controllers/LibroController.php?accion=eliminar&id=<?php echo $l['id']; ?>" class="btn btn-danger btn-xs py-0 px-1" onclick="return confirm('¿Eliminar libro?')">Eliminar</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <?php elseif ($seccion === 'prestamos'): ?>
                        <div class="row">
                            <div class="col-lg-4 mb-4">
                                <h5>Generar Nuevo Préstamo</h5>
                                <form method="POST" action="../Controllers/PrestamoController.php?accion=crear">
                                    <div class="mb-2"><label class="small">Seleccionar Usuario</label>
                                        <select class="form-select form-select-sm" name="idUsuario" required>
                                            <?php foreach ($usuarios as $u): ?>
                                                <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars($u['nombre']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-2"><label class="small">Seleccionar Libro</label>
                                        <select class="form-select form-select-sm" name="idLibro" required>
                                            <?php foreach ($libros as $l): ?>
                                                <option value="<?php echo $l['id']; ?>"><?php echo htmlspecialchars($l['titulo_libro']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-2"><label class="small">Fecha Límite</label><input type="datetime-local" class="form-control form-control-sm" name="fecha_limite" required></div>
                                    <div class="mb-2"><label class="small">Detalles/Notas</label><textarea class="form-control form-control-sm" name="detalles" rows="2" placeholder="Estado del libro al entregar..."></textarea></div>
                                    <button type="submit" class="btn btn-warning btn-sm w-100 mt-2">Registrar Salida</button>
                                </form>
                            </div>
                            <div class="col-lg-8">
                                <h5>Control de Préstamos Activos</h5>
                                <table class="table table-sm table-striped small align-middle">
                                    <thead><tr><th>Libro</th><th>Lector</th><th>Límite</th><th>Estado</th><th>Acciones</th></tr></thead>
                                    <tbody>
                                        <?php foreach ($prestamos as $p): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($p['libro']); ?></td>
                                                <td><?php echo htmlspecialchars($p['usuario']); ?></td>
                                                <td><?php echo htmlspecialchars($p['fecha_limite']); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $p['estado'] === 'pendiente' ? 'bg-warning text-dark' : 'bg-success'; ?>">
                                                        <?php echo htmlspecialchars($p['estado']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($p['estado'] === 'pendiente'): ?>
                                                        <a href="../Controllers/PrestamoController.php?accion=completar&id=<?php echo $p['id']; ?>" class="btn btn-success btn-xs py-0 px-1">Marcar Completado</a>
                                                    <?php else: ?>
                                                        <span class="text-muted small">Sin acciones</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

</body>
</html>