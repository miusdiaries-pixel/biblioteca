<?php

class Libro {
    private ?int $idLibro;
    private string $tituloLibro;
    private int $anioPublicacion;
    private string $autorLibro;


    public function __construct(string $tituloLibro, int $anioPublicacion, string $autorLibro, ?int $idLibro = null) {
        $this->idLibro = $idLibro;
        $this->tituloLibro = $tituloLibro;
        $this->anioPublicacion = $anioPublicacion;
        $this->autorLibro = $autorLibro;
    }

    public function setIdLibro(?int $idLibro): void { $this->idLibro = $idLibro; }
    public function getIdLibro(): ?int { return $this->idLibro; }
    public function setTituloLibro(string $tituloLibro): void { $this->tituloLibro = $tituloLibro; }
    public function getTituloLibro(): string { return $this->tituloLibro; }
    public function setAnioPublicacion(int $anioPublicacion): void { $this->anioPublicacion = $anioPublicacion; }
    public function getAnioPublicacion(): int  { return $this->anioPublicacion; }
    public function setAutor(string $autorLibro): void { $this->autorLibro = $autorLibro; }
    public function getAutor(): string { return $this->autorLibro; }
    public function mostrar(): void { echo "Libro: " . $this->getTituloLibro() . "<br><br>" . "Autor: " .$this->getAutor() . "<br><br>" . "Año de publicación: " . $this->getAnioPublicacion();}
}

?>