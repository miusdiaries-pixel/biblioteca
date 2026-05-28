<?php
require_once __DIR__ . '/../../config/database.php';

class Conexion {
    private static ?\PDO $pdo = null;

    public static function conectar(): \PDO {
        if (self::$pdo === null) {
            try {
                $dsn = "mysql:host=" . \ConfigDB::HOST . 
                       ";dbname=" . \ConfigDB::DBNAME . 
                       ";port=" . \ConfigDB::PORT . ";charset=utf8mb4";
                       
                self::$pdo = new \PDO($dsn, \ConfigDB::USERNAME, \ConfigDB::PASSWORD, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                ]);
            } catch (\PDOException $e) {
                die("Conexión fallida: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
?>
