<?php

class SessionMiddleware {
    
    public static function protegerVista(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario'])) {
            header("Location: ../Views/login.html");
            exit();
        }
    }
}
?>
