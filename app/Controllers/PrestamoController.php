<?php
require_once __DIR__ . '/../Models/conexion.php';
require_once __DIR__ . '/../Models/prestamo.php';

class PrestamoController {
    private PDO $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function procesar(): void {
        $accion = $_GET['accion'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'crear') {
            $prestamo = new Prestamo(
                $_POST['detalles'],
                new DateTime(),
                new DateTime($_POST['fecha_limite']),
                'pendiente',
                intval($_POST['idUsuario']),
                intval($_POST['idLibro'])
            );
            $stmt = $this->db->prepare("INSERT INTO prestamo (detalles, fecha_solicitud, fecha_limite, idUsuario, idLibro, estado) VALUES (:detalles, NOW(), :fecha_limite, :idUsuario, :idLibro, :estado)");
            $stmt->execute([
                'detalles'     => $prestamo->getDetalles(),
                'fecha_limite' => $prestamo->getFechaLimite()->format('Y-m-d'),
                'idUsuario'    => $prestamo->getIdUsuario(),
                'idLibro'      => $prestamo->getIdLibro(),
                'estado'       => $prestamo->getEstado()
            ]);

            $origen = $_POST['origen'] ?? 'admin';
            if ($origen === 'usuario') {
                header("Location: ../Views/dashboard_user.php?seccion=prestamos");
            } else {
                header("Location: ../Views/dashboard_admin.php?seccion=prestamos");
            }
            exit();
        }

        if ($accion === 'completar') {
            $stmt = $this->db->prepare("UPDATE prestamo SET estado = 'completado' WHERE id = :id");
            $stmt->execute(['id' => $_GET['id']]);
            header("Location: ../Views/dashboard_admin.php?seccion=prestamos");
            exit();
        }
    }

    public function listar(): array {
        $sql = "SELECT p.*, per.nombre as usuario, l.titulo_libro as libro 
                FROM prestamo p 
                JOIN persona per ON p.idUsuario = per.id 
                JOIN libro l ON p.idLibro = l.id";
        return $this->db->query($sql)->fetchAll();
    }
}

$prestamoCtrl = new PrestamoController();
if (isset($_GET['accion'])) { $prestamoCtrl->procesar(); }
