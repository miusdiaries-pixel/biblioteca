<?php

class Validador {

    public static function validarNombre(string $nombre): string {
        if (empty($nombre)) {
            return "El nombre es obligatorio";
        }
        return "";
    }

    public static function validarEmail(string $email): string {
        // CORRECCIÓN: Cambiado $correo por $email
        if (empty($email)) {
            return "El correo es obligatorio";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Correo no válido";
        }
        return "";
    }

    public static function validarClave(string $clave): string {
        if (empty($clave)) {
            return "La contraseña es obligatoria";
        }
        if (strlen($clave) < 6) {
            return "La contraseña debe tener mínimo 6 caracteres";
        }
        if (!preg_match('/[A-Z]/', $clave)) {
            return "La contraseña debe tener al menos una letra mayúscula";
        }
        if (!preg_match('/[a-z]/', $clave)) {
            return "La contraseña debe tener al menos una letra minúscula";
        }
        if (!preg_match('/[0-9]/', $clave)) {
            return "La contraseña debe tener al menos un número";
        }
        if (!preg_match('/[\W_]/', $clave)) {
            return "La contraseña debe tener al menos un símbolo";
        }
        return "";
    }
}

?>
