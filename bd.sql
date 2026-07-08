-- Script para crear base de datos y tablas (MyISAM)
-- Zona horaria: Argentina (UTC-3)

-- Configuración inicial
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "-03:00";
SET NAMES utf8mb4 COLLATE utf8mb4_general_ci;

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS `ReservationSystem` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ReservationSystem`;

-- --------------------------------------------------------
-- Tabla: tabla (reservas)
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tabla`;
CREATE TABLE `tabla` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `nombreapellido` VARCHAR(100) NOT NULL,
  `curso` VARCHAR(45) NOT NULL,
  `materia` VARCHAR(45) DEFAULT NULL,
  `horario` TIME NOT NULL,
  `horario1` TIME NOT NULL,
  `fecha` DATE NOT NULL,
  `info` VARCHAR(50) NOT NULL,
  `materiales` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Tabla: usuarios (usuarios del sistema)
-- --------------------------------------------------------

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(50) NOT NULL,
  `clave` VARCHAR(255) NOT NULL,
  `NombreYApellido` VARCHAR(255) NOT NULL,
  `esAdmin` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE tabla ADD INDEX idx_fecha (fecha);
ALTER TABLE tabla ADD INDEX idx_fecha_horario (fecha, horario);

-- Confirmar cambios
COMMIT;

-- --------------------------------------------------------
-- Actualizaciones y migraciones de estructura (Nuevas funcionalidades)
-- --------------------------------------------------------
-- Añadir id_usuario a la tabla para vincular reservas con usuarios del sistema
ALTER TABLE tabla ADD COLUMN id_usuario INT(11) DEFAULT NULL COMMENT 'Añadido para relacionar la reserva con el usuario';

-- Migraciones V2.1: Teléfonos, Notificaciones y Cancelaciones
ALTER TABLE `usuarios` 
ADD COLUMN `telefono` VARCHAR(20) DEFAULT NULL,
ADD COLUMN `tipo_telefono` ENUM('whatsapp', 'celular_sin_wsp', 'fijo') DEFAULT NULL,
ADD COLUMN `modal_v2_visto` TINYINT(1) NOT NULL DEFAULT 0,
ADD COLUMN `foto_perfil` VARCHAR(255) DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `reservas_canceladas` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `id_usuario_origen` INT(11) DEFAULT NULL,
  `id_cancelador` INT(11) DEFAULT NULL,
  `info_reserva` VARCHAR(255) NOT NULL,
  `motivo` TEXT NOT NULL,
  `fecha_cancelacion` DATETIME NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `notificaciones` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `id_usuario_origen` INT(11) DEFAULT NULL,
  `tipo` ENUM('cancelacion', 'cambio_fecha') NOT NULL,
  `mensaje` TEXT NOT NULL,
  `fecha` DATETIME NOT NULL,
  `leido` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
