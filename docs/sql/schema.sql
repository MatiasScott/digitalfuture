-- =========================================================
-- SISTEMA WEB DEL CONGRESO - ESTRUCTURA BD (3NF)
-- Motor: MySQL 8+
-- Charset: utf8mb4
-- =========================================================

CREATE DATABASE IF NOT EXISTS congreso_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE congreso_db;

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS roles_admin (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(40) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS usuarios_admin (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(150) NOT NULL,
    correo VARCHAR(180) NOT NULL UNIQUE,
    usuario VARCHAR(80) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(40) NOT NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuarios_admin_rol (rol),
    CONSTRAINT fk_usuarios_admin_rol
        FOREIGN KEY (rol) REFERENCES roles_admin(codigo)
        ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tipos_entrada (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL UNIQUE,
    precio DECIMAL(10,2) NOT NULL,
    descripcion VARCHAR(255) NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS participantes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    primer_nombre VARCHAR(80) NOT NULL,
    segundo_nombre VARCHAR(80) NULL,
    primer_apellido VARCHAR(80) NOT NULL,
    segundo_apellido VARCHAR(80) NULL,
    correo VARCHAR(180) NOT NULL,
    cedula VARCHAR(30) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    institucion VARCHAR(160) NOT NULL,
    ciudad VARCHAR(120) NOT NULL,
    pais VARCHAR(120) NOT NULL,
    tipo_entrada_id BIGINT UNSIGNED NOT NULL,
    estado ENUM('registrado', 'pago_aprobado', 'pago_rechazado', 'asistencia_confirmada') NOT NULL DEFAULT 'registrado',
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_participantes_correo (correo),
    UNIQUE KEY uk_participantes_cedula (cedula),
    INDEX idx_participantes_tipo_entrada (tipo_entrada_id),
    INDEX idx_participantes_estado (estado),
    CONSTRAINT fk_participantes_tipo_entrada
        FOREIGN KEY (tipo_entrada_id) REFERENCES tipos_entrada(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pagos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    participante_id BIGINT UNSIGNED NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    metodo_pago VARCHAR(50) NOT NULL,
    transaction_id VARCHAR(140) NULL,
    referencia VARCHAR(140) NULL,
    estado ENUM('pendiente', 'aprobado', 'rechazado') NOT NULL DEFAULT 'pendiente',
    fecha_pago DATETIME NOT NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_pagos_participante (participante_id),
    INDEX idx_pagos_estado (estado),
    INDEX idx_pagos_metodo (metodo_pago),
    CONSTRAINT fk_pagos_participante
        FOREIGN KEY (participante_id) REFERENCES participantes(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS comprobantes_pago (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pago_id BIGINT UNSIGNED NOT NULL,
    archivo VARCHAR(255) NOT NULL,
    ruta VARCHAR(255) NOT NULL,
    tipo_archivo VARCHAR(20) NOT NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_comprobante_pago (pago_id),
    INDEX idx_comprobantes_tipo_archivo (tipo_archivo),
    CONSTRAINT fk_comprobantes_pago
        FOREIGN KEY (pago_id) REFERENCES pagos(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS historial_estados_pago (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pago_id BIGINT UNSIGNED NOT NULL,
    estado ENUM('pendiente', 'aprobado', 'rechazado') NOT NULL,
    observacion VARCHAR(255) NULL,
    admin_usuario_id BIGINT UNSIGNED NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_historial_pago_id (pago_id),
    INDEX idx_historial_admin_id (admin_usuario_id),
    CONSTRAINT fk_historial_pago
        FOREIGN KEY (pago_id) REFERENCES pagos(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_historial_admin
        FOREIGN KEY (admin_usuario_id) REFERENCES usuarios_admin(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS asistencias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    participante_id BIGINT UNSIGNED NOT NULL,
    estado ENUM('presente', 'ausente') NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_asistencias_participante (participante_id),
    INDEX idx_asistencias_fecha (fecha),
    CONSTRAINT fk_asistencias_participante
        FOREIGN KEY (participante_id) REFERENCES participantes(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Tablas de escalabilidad para futuros eventos/congresos.
CREATE TABLE IF NOT EXISTS congresos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(180) NOT NULL,
    descripcion TEXT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado ENUM('borrador', 'publicado', 'cerrado') NOT NULL DEFAULT 'borrador',
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS eventos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    congreso_id BIGINT UNSIGNED NOT NULL,
    titulo VARCHAR(180) NOT NULL,
    descripcion TEXT NULL,
    fecha DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_eventos_congreso (congreso_id),
    CONSTRAINT fk_eventos_congreso
        FOREIGN KEY (congreso_id) REFERENCES congresos(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Seed base.
INSERT INTO roles_admin (codigo, nombre, descripcion)
VALUES
('super_admin', 'Super Administrador', 'Acceso total al sistema'),
('admin', 'Administrador', 'Gestion operativa del congreso')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre), descripcion = VALUES(descripcion);

INSERT INTO tipos_entrada (nombre, precio, descripcion, estado)
VALUES
('Estudiante', 25.00, 'Entrada para estudiantes', 'activo'),
('Profesional', 60.00, 'Entrada general para profesionales', 'activo'),
('VIP', 120.00, 'Entrada premium con beneficios exclusivos', 'activo')
ON DUPLICATE KEY UPDATE precio = VALUES(precio), descripcion = VALUES(descripcion), estado = VALUES(estado);

-- Usuario inicial de ejemplo (cambiar password tras primer acceso).
-- Password plano: Admin123*
INSERT INTO usuarios_admin (nombres, correo, usuario, password, rol, estado)
VALUES (
    'Super Admin',
    'admin@congreso.com',
    'admin',
    '$2y$10$3LzuLHE1LriAT.y4Nf/o/uEql4w8Pa1GW4vQhtlhvWg0aTO2ueN1m',
    'super_admin',
    'activo'
)
ON DUPLICATE KEY UPDATE
    nombres = VALUES(nombres),
    rol = VALUES(rol),
    estado = VALUES(estado);
