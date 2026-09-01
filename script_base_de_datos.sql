-- Creación de la Base de Datos
CREATE DATABASE TuTenderoDB;
GO

USE TuTenderoDB;
GO

-- Creación de la tabla 
CREATE TABLE reportes_whatsapp (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    estado VARCHAR(20) NOT NULL,
    codigo_error VARCHAR(10) NULL,
    detalle_error VARCHAR(255) NULL,
    fecha_envio DATETIME DEFAULT GETDATE() NOT NULL
);
GO

-- Datos insertados
INSERT INTO reportes_whatsapp (nombre, telefono, estado, codigo_error, detalle_error)
VALUES 
('Juan Perez', '555-1234', 'Enviado', NULL, NULL),
('Maria Lopez', '555-5678', 'Error', '404', 'Número no encontrado'),
('Carlos Sanchez', '555-8765', 'Enviado', NULL, NULL),
('Ana Torres', '555-4321', 'Error', '500', 'Error interno del servidor'),
('Luis Ramirez', '555-1111', 'Enviado', NULL, NULL),
('Sofia Martinez', '555-2222', 'Error', '403', 'Acceso denegado'),
('Diego Fernandez', '555-3333', 'Enviado', NULL, NULL);
GO