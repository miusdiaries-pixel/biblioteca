<?php

class Prestamo
{
    private ?int $idPrestamo;
    private string $detalles;
    private DateTime $fechaSolicitud; 
    private DateTime $fechaLimite;
    private string $estado;
    private int $idUsuario;
    private int $idLibro;

    public function __construct(string $detalles, DateTime $fechaSolicitud, DateTime $fechaLimite, string $estado, int $idUsuario, int $idLibro, ?int $idPrestamo = null)
    {
        $this->idPrestamo = $idPrestamo;
        $this->detalles = $detalles;
        $this->fechaSolicitud = $fechaSolicitud;
        $this->fechaLimite = $fechaLimite;
        $this->estado = $estado;
        $this->idUsuario = $idUsuario;
        $this->idLibro = $idLibro;
    }

    public function setIdPrestamo(?int $idPrestamo): void { $this->idPrestamo = $idPrestamo; }
    public function getIdPrestamo(): ?int { return $this->idPrestamo; }

    public function setDetalles(string $detalles): void { $this->detalles = $detalles; }
    public function getDetalles(): string { return $this->detalles; }

    public function setFechaSolicitud(DateTime $fechaSolicitud): void { $this->fechaSolicitud = $fechaSolicitud; }
    public function getFechaSolicitud(): DateTime { return $this->fechaSolicitud; }

    public function setFechaLimite(DateTime $fechaLimite): void { $this->fechaLimite = $fechaLimite; }
    public function getFechaLimite(): DateTime { return $this->fechaLimite; }

    public function setEstado(string $estado): void { $this->estado = $estado; }
    public function getEstado(): string { return $this->estado; }

    public function setIdUsuario(int $idUsuario): void { $this->idUsuario = $idUsuario; }
    public function getIdUsuario(): int { return $this->idUsuario; }

    public function setIdLibro(int $idLibro): void { $this->idLibro = $idLibro; }
    public function getIdLibro(): int { return $this->idLibro; }

    public function mostrar(): void
    {
        echo "ID Préstamo: " . $this->getIdPrestamo() . "<br>";
        echo "Detalles: " . $this->getDetalles() . "<br>";
        echo "Estado: " . $this->getEstado() . "<br>";
        echo "Fecha Solicitud: " . $this->fechaSolicitud->format('Y-m-d H:i:s') . "<br>";
        echo "Fecha Límite: " . $this->fechaLimite->format('Y-m-d H:i:s') . "<br><br>";
    }
}

?>
