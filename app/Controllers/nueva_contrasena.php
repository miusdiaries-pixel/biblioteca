<?php
require_once '../Models/conexion.php';
require_once '../Models/validar.php';

class PasswordController {
    private PDO $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function restablecer(): array {
        $token = $_GET['token'] ?? '';
        $mensaje = '';
        $tipoAlerta = '';
        $tokenValido = false;
        $correo = '';

        if (empty($token)) {
            return ['mensaje' => "Token inválido.", 'tipoAlerta' => "danger", 'tokenValido' => false];
        }

        try {
            // 1. Validar el token con PDO
            $stmt = $this->db->prepare("SELECT email FROM tokens_recuperacion WHERE token = :token AND expira > NOW()");
            $stmt->execute(['token' => $token]);
            $fila = $stmt->fetch();

            if ($fila) {
                $tokenValido = true;
                $correo = $fila['email'];
            } else {
                return ['mensaje' => "El enlace es inválido o ha expirado.", 'tipoAlerta' => "danger", 'tokenValido' => false];
            }

            // 2. Procesar el formulario POST si el token es válido
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tokenValido) {
                $clave        = $_POST['password'] ?? '';
                $claveConfirm = $_POST['password_confirm'] ?? '';
                
                // Usamos la clase POO Validador que creaste antes
                $errClave = Validador::validarClave($clave);

                if ($errClave) {
                    $mensaje    = $errClave;
                    $tipoAlerta = "danger";
                } elseif ($clave !== $claveConfirm) {
                    $mensaje    = "Las contraseñas no coinciden.";
                    $tipoAlerta = "danger";
                } else {
                    $claveHash = password_hash($clave, PASSWORD_DEFAULT);

                    // Actualizar contraseña del usuario
                    $upd = $this->db->prepare("UPDATE persona SET clave = :clave WHERE email = :email");
                    $upd->execute(['clave' => $claveHash, 'email' => $correo]);

                    // Eliminar el token usado
                    $del = $this->db->prepare("DELETE FROM tokens_recuperacion WHERE token = :token");
                    $del->execute(['token' => $token]);

                    $mensaje     = "Contraseña actualizada correctamente.";
                    $tipoAlerta  = "success";
                    $tokenValido = false;
                }
            }
        } catch (PDOException $e) {
            $mensaje    = "Error en el servidor de base de datos.";
            $tipoAlerta = "danger";
        }

        // Retornamos el estado para que la vista HTML pueda dibujar las alertas
        return [
            'mensaje'     => $mensaje,
            'tipoAlerta'  => $tipoAlerta,
            'tokenValido' => $tokenValido
        ];
    }
}

// Ejecución del controlador
$controller = new PasswordController();
$resultadoView = $controller->restablecer();

// Las variables quedan disponibles para tu vista HTML
$mensaje     = $resultadoView['mensaje'];
$tipoAlerta  = $resultadoView['tipoAlerta'];
$tokenValido = $resultadoView['tokenValido'];
?>
