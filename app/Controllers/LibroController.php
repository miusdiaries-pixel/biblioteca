<?php
require_once __DIR__ . '/../Models/conexion.php';
require_once __DIR__ . '/../Models/libro.php';

class LibroController {
    private PDO $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function procesar(): void {
        $accion = $_GET['accion'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'crear') {
            $libro = new Libro(
                $_POST['titulo_libro'],
                intval($_POST['anio_publicacion']),
                $_POST['autor']
            );
            $stmt = $this->db->prepare("INSERT INTO libro (titulo_libro, anio_publicacion, autor) VALUES (:titulo, :anio, :autor)");
            $stmt->execute([
                'titulo' => $libro->getTituloLibro(),
                'anio'   => $libro->getAnioPublicacion(),
                'autor'  => $libro->getAutor()
            ]);
            header("Location: ../Views/dashboard_admin.php?seccion=libros");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'editar') {
            $libro = new Libro(
                $_POST['titulo_libro'],
                intval($_POST['anio_publicacion']),
                $_POST['autor'],
                intval($_GET['id'])
            );
            $stmt = $this->db->prepare("UPDATE libro SET titulo_libro = :titulo, anio_publicacion = :anio, autor = :autor WHERE id = :id");
            $stmt->execute([
                'titulo' => $libro->getTituloLibro(),
                'anio'   => $libro->getAnioPublicacion(),
                'autor'  => $libro->getAutor(),
                'id'     => $libro->getIdLibro()
            ]);
            header("Location: ../Views/dashboard_admin.php?seccion=libros");
            exit();
        }

        if ($accion === 'eliminar') {
            $stmt = $this->db->prepare("DELETE FROM libro WHERE id = :id");
            $stmt->execute(['id' => $_GET['id']]);
            header("Location: ../Views/dashboard_admin.php?seccion=libros");
            exit();
        }
    }

    public function listar(): array {
        return $this->db->query("SELECT * FROM libro")->fetchAll();
    }
}

$libroCtrl = new LibroController();
if (isset($_GET['accion'])) { $libroCtrl->procesar(); }
