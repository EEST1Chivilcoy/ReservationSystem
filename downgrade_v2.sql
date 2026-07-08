-- Downgrade de la versión V2.1 a la versión V2.0

-- 1. Eliminar tablas creadas para notificaciones y cancelaciones
DROP TABLE IF EXISTS `notificaciones`;
DROP TABLE IF EXISTS `reservas_canceladas`;

-- 2. Eliminar columnas agregadas a la tabla usuarios
ALTER TABLE `usuarios` 
DROP COLUMN `telefono`,
DROP COLUMN `tipo_telefono`,
DROP COLUMN `modal_v2_visto`,
DROP COLUMN `foto_perfil`;

-- 3. Eliminar la relación de la tabla de reservas (tabla)
ALTER TABLE `tabla` 
DROP COLUMN `id_usuario`;
