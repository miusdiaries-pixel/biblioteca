<?php
require_once __DIR__ . '/../Models/conexion.php';
require_once __DIR__ . '/../Models/libro.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

class LibroController {
    private PDO $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    private function validar(string $titulo, string $autor, string $anio): array {
        $errores = [];
        if (empty(trim($titulo)))  $errores[] = "El título es obligatorio.";
        if (empty(trim($autor)))   $errores[] = "El autor es obligatorio.";
        if (empty($anio) || !is_numeric($anio) || intval($anio) < 1000 || intval($anio) > 2100) {
            $errores[] = "El año debe ser un número válido (1000-2100).";
        }
        return $errores;
    }

    public function procesar(): void {
        $accion = $_GET['accion'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'crear') {
            $titulo = $_POST['titulo_libro'] ?? '';
            $autor  = $_POST['autor'] ?? '';
            $anio   = $_POST['anio_publicacion'] ?? '';

            $errores = $this->validar($titulo, $autor, $anio);
            if (!empty($errores)) {
                $_SESSION['errores'] = $errores;
                header("Location: ../Views/dashboard_admin.php?seccion=libros");
                exit();
            }

            $libro = new Libro($titulo, intval($anio), $autor);
            $stmt = $this->db->prepare("INSERT INTO libro (titulo_libro, anio_publicacion, autor) VALUES (:titulo, :anio, :autor)");
            $stmt->execute(['titulo'=>$libro->getTituloLibro(),'anio'=>$libro->getAnioPublicacion(),'autor'=>$libro->getAutor()]);
            header("Location: ../Views/dashboard_admin.php?seccion=libros");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'editar') {
            $id     = intval($_GET['id'] ?? 0);
            $titulo = $_POST['titulo_libro'] ?? '';
            $autor  = $_POST['autor'] ?? '';
            $anio   = $_POST['anio_publicacion'] ?? '';

            $errores = $this->validar($titulo, $autor, $anio);
            if (!empty($errores)) {
                $_SESSION['errores'] = $errores;
                header("Location: ../Views/editar_libro.php?id=$id");
                exit();
            }

            $libro = new Libro($titulo, intval($anio), $autor, $id);
            $stmt = $this->db->prepare("UPDATE libro SET titulo_libro=:titulo, anio_publicacion=:anio, autor=:autor WHERE id=:id");
            $stmt->execute(['titulo'=>$libro->getTituloLibro(),'anio'=>$libro->getAnioPublicacion(),'autor'=>$libro->getAutor(),'id'=>$libro->getIdLibro()]);
            header("Location: ../Views/dashboard_admin.php?seccion=libros");
            exit();
        }

        if ($accion === 'eliminar') {
            $id = intval($_GET['id'] ?? 0);
            $this->db->prepare("DELETE FROM libro WHERE id=:id")->execute(['id'=>$id]);
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
