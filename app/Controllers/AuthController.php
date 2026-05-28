<?php
require_once __DIR__ . '/../Models/conexion.php';

class AuthController {

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../Views/login.php');
            exit();
        }

        $correo = $_POST['email'] ?? '';
        $clave  = $_POST['password'] ?? '';

        try {
            $db = Conexion::conectar();
            $stmt = $db->prepare("SELECT id, nombre, clave, rol FROM persona WHERE email = :email");
            $stmt->execute(['email' => $correo]);
            $usuario = $stmt->fetch();

            if ($usuario && password_verify($clave, $usuario['clave'])) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                session_regenerate_id(true);
                $_SESSION['id_usuario'] = $usuario['id'];
                $_SESSION['usuario']    = $usuario['nombre'];
                $_SESSION['rol']        = $usuario['rol'];

                if ($usuario['rol'] === 'admin') {
                    header("Location: ../Views/dashboard_admin.php");
                } else {
                    header("Location: ../Views/dashboard_user.php");
                }
                exit();
            }

            header("Location: ../Views/loginerroneo.php");
            exit();

        } catch (PDOException $e) {
            header("Location: ../Views/loginerroneo.php");
            exit();
        }
    }

    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
        header("Location: /Biblioteca/app/Views/login.php");
        exit();
    }
}
