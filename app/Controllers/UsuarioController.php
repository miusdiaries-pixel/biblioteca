<?php
require_once __DIR__ . '/../Models/conexion.php';
require_once __DIR__ . '/../Models/usuario.php';
require_once __DIR__ . '/../Models/validar.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

class UsuarioController {
    private PDO $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function procesar(): void {
        $accion = $_GET['accion'] ?? '';

        // Editar perfil propio (usuario o admin)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'actualizar') {
            $idUsuario = $_SESSION['id_usuario'];
            $nombre    = trim($_POST['nombre'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $edad      = $_POST['edad'] ?? '';
            $password  = $_POST['password'] ?? '';

            $errores = [];
            $errNombre = Validador::validarNombre($nombre);
            $errEmail  = Validador::validarEmail($email);
            if ($errNombre) $errores[] = $errNombre;
            if ($errEmail)  $errores[] = $errEmail;
            if (empty($edad) || !is_numeric($edad) || intval($edad) <= 0) {
                $errores[] = "La edad debe ser un número válido.";
            }
            if (!empty($password)) {
                $errClave = Validador::validarClave($password);
                if ($errClave) $errores[] = $errClave;
            }

            if (!empty($errores)) {
                $_SESSION['errores'] = $errores;
                header("Location: ../Views/editar_usuario.php");
                exit();
            }

            $usuario = new Usuario($idUsuario, $nombre, intval($edad), $email, $password);

            if (!empty($password)) {
                $hash = password_hash($usuario->getClave(), PASSWORD_BCRYPT);
                $sql = "UPDATE persona SET nombre=:nombre, email=:email, edad=:edad, clave=:clave WHERE id=:id";
                $params = ['nombre'=>$usuario->getNombre(),'email'=>$usuario->getEmail(),'edad'=>$usuario->getEdad(),'clave'=>$hash,'id'=>$usuario->getIdPersona()];
            } else {
                $sql = "UPDATE persona SET nombre=:nombre, email=:email, edad=:edad WHERE id=:id";
                $params = ['nombre'=>$usuario->getNombre(),'email'=>$usuario->getEmail(),'edad'=>$usuario->getEdad(),'id'=>$usuario->getIdPersona()];
            }

            $this->db->prepare($sql)->execute($params);
            $_SESSION['usuario'] = $usuario->getNombre();

            $destino = $_SESSION['rol'] === 'admin' ? '../Views/dashboard_admin.php' : '../Views/dashboard_user.php';
            header("Location: $destino");
            exit();
        }

        // Editar usuario desde panel admin
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'actualizar_admin') {
            $id     = intval($_GET['id'] ?? 0);
            $nombre = trim($_POST['nombre'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $edad   = $_POST['edad'] ?? '';
            // Si el admin edita su propia cuenta, ignorar el rol del POST y mantener 'admin'
            if (intval($_GET['id'] ?? 0) === intval($_SESSION['id_usuario'])) {
                $rol = 'admin';
            } else {
                $rol = $_POST['rol'] ?? 'usuario';
            }
            $password = $_POST['password'] ?? '';

            $errores = [];
            $errNombre = Validador::validarNombre($nombre);
            $errEmail  = Validador::validarEmail($email);
            if ($errNombre) $errores[] = $errNombre;
            if ($errEmail)  $errores[] = $errEmail;
            if (empty($edad) || !is_numeric($edad) || intval($edad) <= 0) {
                $errores[] = "La edad debe ser un número válido.";
            }
            if (!in_array($rol, ['admin','usuario'])) {
                $errores[] = "Rol no válido.";
            }
            if (!empty($password)) {
                $errClave = Validador::validarClave($password);
                if ($errClave) $errores[] = $errClave;
            }

            if (!empty($errores)) {
                $_SESSION['errores'] = $errores;
                header("Location: ../Views/editar_usuario_admin.php?id=$id");
                exit();
            }

            $usuario = new Usuario($id, $nombre, intval($edad), $email, $password);

            if (!empty($password)) {
                $hash = password_hash($usuario->getClave(), PASSWORD_BCRYPT);
                $sql = "UPDATE persona SET nombre=:nombre, email=:email, edad=:edad, clave=:clave, rol=:rol WHERE id=:id";
                $params = ['nombre'=>$usuario->getNombre(),'email'=>$usuario->getEmail(),'edad'=>$usuario->getEdad(),'clave'=>$hash,'rol'=>$rol,'id'=>$usuario->getIdPersona()];
            } else {
                $sql = "UPDATE persona SET nombre=:nombre, email=:email, edad=:edad, rol=:rol WHERE id=:id";
                $params = ['nombre'=>$usuario->getNombre(),'email'=>$usuario->getEmail(),'edad'=>$usuario->getEdad(),'rol'=>$rol,'id'=>$usuario->getIdPersona()];
            }

            $this->db->prepare($sql)->execute($params);
            if ($id === intval($_SESSION['id_usuario'])) {
                $_SESSION['usuario'] = $usuario->getNombre();
            }
            header("Location: ../Views/dashboard_admin.php?seccion=usuarios");
            exit();
        }

        // Eliminar usuario desde panel admin
        if ($accion === 'eliminar') {
            $id = intval($_GET['id'] ?? 0);
            $this->db->prepare("DELETE FROM persona WHERE id=:id")->execute(['id'=>$id]);
            header("Location: ../Views/dashboard_admin.php?seccion=usuarios");
            exit();
        }
    }
}

$usuarioCtrl = new UsuarioController();
if (isset($_GET['accion'])) { $usuarioCtrl->procesar(); }
