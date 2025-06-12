-- Script para crear base de datos y tablas (MyISAM)
-- Incluye dos usuarios por defecto con contraseñas hasheadas
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

-- --------------------------------------------------------
-- Inserción de usuarios predeterminados con claves BCRYPT
-- --------------------------------------------------------

INSERT INTO `usuarios` (`usuario`, `clave`, `NombreYApellido`, `esAdmin`) VALUES
('admin', '$2y$10$asqZ1UiHQ.qLsA28QaZ7uONwZQv2rqTEJo/8yMP67UPHFl3yA7PxW', 'Administrador del Sistema', 1),
('prueba', '$2y$10$sm1phFtuoyZ4R9PN3E3YxeZd96rznq6Aax56BGRZ1kEX3GR8DdZga', 'Usuario de Prueba', 0);

-- Confirmar cambios
COMMIT;
