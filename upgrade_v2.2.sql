-- Actualización de Base de Datos V2.2
-- Mejoras en el sistema de cancelación de reservas

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "-03:00";
SET NAMES utf8mb4 COLLATE utf8mb4_general_ci;

USE `ReservationSystem`;

-- ========================================================================
-- MEJORA 1: Agregar campo para identificar notificaciones dirigidas a admins
-- ========================================================================
ALTER TABLE `notificaciones` ADD COLUMN `para_admins` TINYINT(1) DEFAULT 0 COMMENT 'Si es 1, la notificación es para todos los administradores' AFTER `leido`;

-- ========================================================================
-- MEJORA 2: Agregar índice para búsqueda eficiente de notificaciones de admins
-- ========================================================================
ALTER TABLE `notificaciones` ADD INDEX idx_para_admins_leido (para_admins, leido);

-- ========================================================================
-- MEJORA 3: Agregar campo para rastrear si la notificación fue enviada al usuario
-- ========================================================================
ALTER TABLE `reservas_canceladas` ADD COLUMN `notificacion_enviada` TINYINT(1) DEFAULT 0 COMMENT 'Si es 1, se envió WhatsApp/SMS al usuario' AFTER `fecha_cancelacion`;

-- Confirmar cambios
COMMIT;
