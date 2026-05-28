<?php
require_once '../Models/conexion.php';
require_once '../Models/validar.php';

class RegisterController {
    private PDO $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function registrar(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['errores'] = [];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../Views/registroerroneo.php');
            exit();
        }

        $nombre = $_POST['nombre'] ?? '';
        $correo = $_POST['email'] ?? '';
        $clave  = $_POST['password'] ?? '';
        $edad   = $_POST['edad'] ?? '';
        
        $origen = $_POST['origen'] ?? 'publico'; 
        
        if ($origen === 'admin') {
            $rol = $_POST['rol'] ?? 'usuario'; 
        } else {
            $rol = 'usuario'; 
        }

        $errNombre = Validador::validarNombre($nombre);
        $errCorreo = Validador::validarEmail($correo);
        $errClave  = Validador::validarClave($clave);

        if ($errNombre) $_SESSION['errores'][] = $errNombre;
        if ($errCorreo) $_SESSION['errores'][] = $errCorreo;
        if ($errClave)  $_SESSION['errores'][] = $errClave;

        if (empty($edad) || !is_numeric($edad) || intval($edad) <= 0) {
            $_SESSION['errores'][] = "La edad debe ser un número entero válido.";
        }

        if ($rol !== 'admin' && $rol !== 'usuario') {
            $_SESSION['errores'][] = "El rol seleccionado no es válido.";
        }

        try {
            if (empty($_SESSION['errores'])) {
                $check = $this->db->prepare("SELECT id FROM persona WHERE email = :email");
                $check->execute(['email' => $correo]);
                if ($check->fetch()) {
                    $_SESSION['errores'][] = "El correo electrónico ya está registrado.";
                }
            }

            if (empty($_SESSION['errores'])) {
                $claveHash = password_hash($clave, PASSWORD_DEFAULT);
                
                $stmt = $this->db->prepare("INSERT INTO persona (nombre, email, clave, edad, rol) VALUES (:nombre, :email, :clave, :edad, :rol)");
                
                $exito = $stmt->execute([
                    'nombre' => $nombre,
                    'email'  => $correo,
                    'clave'  => $claveHash,
                    'edad'   => intval($edad),
                    'rol'    => $rol
                ]);

                if ($exito) {
                    if ($origen === 'admin') {
                        header('Location: ../Views/dashboard_admin.php?seccion=usuarios'); 
                    } else {
                        // LUGAR CORRECTO: Añadimos el mensaje discreto en la sesión
                        $_SESSION['registro_exito'] = "¡Registro exitoso! Ya puedes iniciar sesión con tus credenciales.";
                        // Cambiamos el archivo de destino a login.php
                        header('Location: ../Views/login.php');
                    }
                    exit();
                } else {
                    $_SESSION['errores'][] = "Error al intentar registrar el usuario.";
                }
            }
        } catch (PDOException $e) {
            $_SESSION['errores'][] = "Error crítico en base de datos: " . $e->getMessage();
        }

        header('Location: ../Views/registroerroneo.php');
        exit();
    }
}

$register = new RegisterController();
$register->registrar();
?>
