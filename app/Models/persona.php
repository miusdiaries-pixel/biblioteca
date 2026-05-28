<?php

abstract class Persona {
    private ?int $idPersona; 
    private string $nombre;
    private int $edad;
    private string $email;
    private string $clave;

    public function __construct(string $nombre, int $edad, string $email, string $clave, ?int $idPersona = null) {
        $this->idPersona = $idPersona;
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->email = $email;
        $this->clave = $clave;
    }

    public function setIdPersona(int $idPersona): void { $this->idPersona = $idPersona; }
    public function getIdPersona(): ?int { return $this->idPersona; }
    public function setNombre(string $nombre): void { $this->nombre = $nombre; }
    public function getNombre(): string { return $this->nombre; }
    public function setEdad(int $edad): void { $this->edad = $edad; }
    public function getEdad(): int { return $this->edad; }

    public function setEmail(string $email): void { $this->email = $email; }
    public function getEmail(): string { return $this->email; }

    public function setClave(string $clave): void { $this->clave = $clave; }
    public function getClave(): string { return $this->clave; }

    abstract public function buscar(): void;
}

?>
