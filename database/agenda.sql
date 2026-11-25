
CREATE DATABASE IF NOT EXISTS agenda_contactos
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE agenda_contactos;

CREATE TABLE IF NOT EXISTS contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    foto VARCHAR(255) NOT NULL,
    email VARCHAR(150) NULL,
    direccion VARCHAR(255) NULL,
    notas TEXT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
