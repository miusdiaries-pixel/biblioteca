<?php

class Usuario extends Persona {

    public function __construct(int $idUsuario, string $nombre, int $edad, string $email, string $clave) {
        parent::__construct($nombre, $edad, $email, $clave, $idUsuario);
    }

    public function buscar(): void { echo "Buscando libro"; }

    public function mostrar(): void {
        echo "ID Usuario: " . $this->getIdPersona() . "<br>";
        echo "Usuario: " . $this->getNombre() . "<br>";
        echo "Edad: " . $this->getEdad() . "<br>";
    }
}

?>
