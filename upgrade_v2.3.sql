-- Actualización de Base de Datos V2.3
-- Migrar estado de lectura de notificaciones de global a por-usuario

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "-03:00";
SET NAMES utf8mb4 COLLATE utf8mb4_general_ci;

USE `ReservationSystem`;

-- ========================================================================
-- PASO 1: Crear tabla intermedia para estado de lectura por usuario
-- ========================================================================
CREATE TABLE IF NOT EXISTS `notificaciones_leidas` (
  `id_notificacion` INT(11) NOT NULL,
  `id_usuario` INT(11) NOT NULL,
  `fecha_lectura` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notificacion`, `id_usuario`),
  INDEX idx_usuario (`id_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ========================================================================
-- PASO 2: Migrar datos existentes
-- Para las notificaciones que ya estaban marcadas como leído=1 y para_admins=1,
-- insertar un registro en notificaciones_leidas para cada admin actual.
-- Esto preserva el estado previo: si la notificación estaba "leída" globalmente,
-- ahora estará "leída" para todos los admins existentes.
-- ========================================================================
INSERT IGNORE INTO `notificaciones_leidas` (`id_notificacion`, `id_usuario`, `fecha_lectura`)
SELECT n.ID, u.ID, n.fecha
FROM `notificaciones` n
CROSS JOIN `usuarios` u
WHERE n.leido = 1
  AND n.para_admins = 1
  AND u.esAdmin = 1;

-- Confirmar cambios
COMMIT;
