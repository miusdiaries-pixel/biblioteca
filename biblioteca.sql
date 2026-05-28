DROP DATABASE IF EXISTS biblioteca;

CREATE DATABASE biblioteca;
USE biblioteca;

-- Tabla base para login (Todos heredan de aquí)
CREATE TABLE persona (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nombre   VARCHAR(100) NOT NULL,
    edad     INT          NOT NULL,
    email    VARCHAR(100) UNIQUE NOT NULL,
    clave    VARCHAR(255) NOT NULL,
    rol      ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario'
);

CREATE TABLE libro (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    titulo_libro  VARCHAR(100) NOT NULL,
    anio_publicacion INT NOT NULL,
    autor VARCHAR(100) NOT NULL
);

CREATE TABLE prestamo (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    detalles VARCHAR(500) NOT NULL,
    fecha_solicitud DATETIME NOT NULL,
    fecha_limite DATETIME NOT NULL,
	estado ENUM('pendiente', 'completado') NOT NULL DEFAULT 'pendiente',
    idUsuario INT NOT NULL,
    idLibro INT NOT NULL,
    FOREIGN KEY (idUsuario) REFERENCES persona(id),
    FOREIGN KEY (idLibro) REFERENCES libro(id)
);
