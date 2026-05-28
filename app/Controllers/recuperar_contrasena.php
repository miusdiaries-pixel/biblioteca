<?php
require_once '../Models/conexion.php';
require_once '../Models/validar.php';

class RecoveryController {
    private PDO $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function procesarSolicitud(): array {
        $mensaje = '';
        $tipoAlerta = '';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['mensaje' => '', 'tipoAlerta' => ''];
        }

        $correo = $_POST['email'] ?? '';
        
        // Usamos la clase Validador que creaste previamente
        $errCorreo = Validador::validarEmail($correo);

        if ($errCorreo !== "") {
            return ['mensaje' => "Ingresa un correo válido.", 'tipoAlerta' => "danger"];
        }

        try {
            // Verificar si el usuario existe en la tabla persona
            $stmt = $this->db->prepare("SELECT id FROM persona WHERE email = :email");
            $stmt->execute(['email' => $correo]);
            
            if ($stmt->fetch()) {
                $token  = bin2hex(random_bytes(32));
                $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Eliminar tokens viejos de este correo
                $del = $this->db->prepare("DELETE FROM tokens_recuperacion WHERE email = :email");
                $del->execute(['email' => $correo]);

                // Insertar el nuevo token de recuperación
                $ins = $this->db->prepare("INSERT INTO tokens_recuperacion (email, token, expira) VALUES (:email, :token, :expira)");
                $ins->execute([
                    'email'  => $correo,
                    'token'  => $token,
                    'expira' => $expira
                ]);

                $mensaje = "Se ha generado un enlace de recuperación. Enlace: <a href='nueva_contrasena.php?token=" . htmlspecialchars($token) . "'>Restablecer contraseña</a>";
                $tipoAlerta = "success";
            } else {
                $mensaje = "El correo no está registrado.";
                $tipoAlerta = "danger";
            }
        } catch (PDOException $e) {
            $mensaje = "Error en el servidor de base de datos.";
            $tipoAlerta = "danger";
        }

        return ['mensaje' => $mensaje, 'tipoAlerta' => $tipoAlerta];
    }
}

// Ejecución del controlador para interactuar con la vista HTML
$recovery = new RecoveryController();
$resultadoView = $recovery->procesarSolicitud();

$mensaje = $resultadoView['mensaje'];
$tipoAlerta = $resultadoView['tipoAlerta'];
?>
