<?php
require_once __DIR__ . '/persona.php';

class Admin extends Persona {
    public function __construct(string $nombre, int $edad, string $email, string $clave, int $idAdmin) {
        parent::__construct($nombre, $edad, $email, $clave, $idAdmin);
    }
    public function buscar(): void { echo "Buscando usuarios..."; }
    public function mostrar(): void {
        echo "ID Admin: " . $this->getIdPersona() . "<br>";
        echo "Administrador: " . $this->getNombre() . "<br><br>";
    }
}
