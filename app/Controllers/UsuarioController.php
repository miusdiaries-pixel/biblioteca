<?php
require_once __DIR__ . '/../Models/conexion.php';
require_once __DIR__ . '/../Models/usuario.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

class UsuarioController {
    private PDO $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function procesar(): void {
        $accion = $_GET['accion'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'actualizar') {
            $idUsuario = $_SESSION['id_usuario'];
            $nombre    = $_POST['nombre'];
            $email     = $_POST['email'];
            $edad      = intval($_POST['edad']);
            $password  = $_POST['password'] ?? '';

            $usuario = new Usuario($idUsuario, $nombre, $edad, $email, $password);

            if (!empty($password)) {
                $passwordHash = password_hash($usuario->getClave(), PASSWORD_BCRYPT);
                $sql = "UPDATE persona SET nombre = :nombre, email = :email, edad = :edad, clave = :clave WHERE id = :id";
                $params = [
                    'nombre' => $usuario->getNombre(),
                    'email'  => $usuario->getEmail(),
                    'edad'   => $usuario->getEdad(),
                    'clave'  => $passwordHash,
                    'id'     => $usuario->getIdPersona()
                ];
            } else {
                $sql = "UPDATE persona SET nombre = :nombre, email = :email, edad = :edad WHERE id = :id";
                $params = [
                    'nombre' => $usuario->getNombre(),
                    'email'  => $usuario->getEmail(),
                    'edad'   => $usuario->getEdad(),
                    'id'     => $usuario->getIdPersona()
                ];
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            $_SESSION['usuario'] = $usuario->getNombre();

            header("Location: ../Views/dashboard_user.php");
            exit();
        }
    }
}

$usuarioCtrl = new UsuarioController();
if (isset($_GET['accion'])) { $usuarioCtrl->procesar(); }
