<?php
require_once __DIR__ . '/../Models/conexion.php';
require_once __DIR__ . '/../Models/prestamo.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

class PrestamoController {
    private PDO $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function procesar(): void {
        $accion = $_GET['accion'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'crear') {
            $detalles    = trim($_POST['detalles'] ?? '');
            $fechaLimite = $_POST['fecha_limite'] ?? '';
            $idUsuario   = intval($_POST['idUsuario'] ?? 0);
            $idLibro     = intval($_POST['idLibro'] ?? 0);

            $errores = [];
            if (empty($detalles))    $errores[] = "Los detalles son obligatorios.";
            if (empty($fechaLimite)) $errores[] = "La fecha límite es obligatoria.";
            if ($idUsuario <= 0)     $errores[] = "Debe seleccionar un usuario.";
            if ($idLibro <= 0)       $errores[] = "Debe seleccionar un libro.";

            $origen = $_POST['origen'] ?? 'admin';
            // Verificar que el libro no tenga un préstamo pendiente
            if (empty($errores) && $idLibro > 0) {
                $check = $this->db->prepare("SELECT id FROM prestamo WHERE idLibro = :idLibro AND estado = 'pendiente'");
                $check->execute(['idLibro' => $idLibro]);
                if ($check->fetch()) {
                    $errores[] = "Este libro ya tiene un préstamo pendiente y no está disponible.";
                }
            }

            if (!empty($errores)) {
                $_SESSION['errores'] = $errores;
                $dest = $origen === 'usuario' ? '../Views/dashboard_user.php?seccion=prestamos' : '../Views/dashboard_admin.php?seccion=prestamos';
                header("Location: $dest");
                exit();
            }

            $prestamo = new Prestamo($detalles, new DateTime(), new DateTime($fechaLimite), 'pendiente', $idUsuario, $idLibro);
            $stmt = $this->db->prepare("INSERT INTO prestamo (detalles, fecha_solicitud, fecha_limite, idUsuario, idLibro, estado) VALUES (:detalles, NOW(), :fecha_limite, :idUsuario, :idLibro, :estado)");
            $stmt->execute([
                'detalles'     => $prestamo->getDetalles(),
                'fecha_limite' => $prestamo->getFechaLimite()->format('Y-m-d H:i:s'),
                'idUsuario'    => $prestamo->getIdUsuario(),
                'idLibro'      => $prestamo->getIdLibro(),
                'estado'       => $prestamo->getEstado()
            ]);

            $dest = $origen === 'usuario' ? '../Views/dashboard_user.php?seccion=prestamos' : '../Views/dashboard_admin.php?seccion=prestamos';
            header("Location: $dest");
            exit();
        }

        if ($accion === 'completar') {
            $id = intval($_GET['id'] ?? 0);
            $this->db->prepare("UPDATE prestamo SET estado='completado' WHERE id=:id")->execute(['id'=>$id]);
            header("Location: ../Views/dashboard_admin.php?seccion=prestamos");
            exit();
        }

        if ($accion === 'eliminar') {
            $id = intval($_GET['id'] ?? 0);
            $this->db->prepare("DELETE FROM prestamo WHERE id=:id")->execute(['id'=>$id]);
            header("Location: ../Views/dashboard_admin.php?seccion=prestamos");
            exit();
        }
    }

    public function listar(): array {
        return $this->db->query("SELECT p.*, per.nombre as usuario, l.titulo_libro as libro FROM prestamo p JOIN persona per ON p.idUsuario = per.id JOIN libro l ON p.idLibro = l.id")->fetchAll();
    }
}

$prestamoCtrl = new PrestamoController();
if (isset($_GET['accion'])) { $prestamoCtrl->procesar(); }
