# Instrucciones para agentes de IA (AGENTS.md)

Propósito: ayudar a un agente de codificación a entender rápidamente cómo montar, probar y modificar este repositorio sin romper dependencias ni datos.

Resumen rápido
- Levantar con Docker: `docker-compose up -d --build` ([docker-compose.yml](docker-compose.yml#L1)).
- Build manual: `docker build -t reservation-web .` y `docker run -p 80:80 reservation-web` ([Dockerfile](Dockerfile#L1)).
- Desarrollo local: `cd ReservationSystem && composer install` y `php -S localhost:8000 -t .` ([ReservationSystem/composer.json](ReservationSystem/composer.json#L1)).
- Importar esquema: `mysql -u <user> -p ReservationSystem < bd.sql` ([bd.sql](bd.sql#L1)).

Archivos clave (referencias)
- [README.md](README.md#L1) — visión general y comandos.
- [docker-compose.yml](docker-compose.yml#L1) — orquestación local y variables de entorno de ejemplo.
- [Dockerfile](Dockerfile#L1) — pasos de build y `composer install` en contenedor.
- [bd.sql](bd.sql#L1), [upgrade_v2.2.sql](upgrade_v2.2.sql#L1) — scripts de base de datos.
- [ReservationSystem/composer.json](ReservationSystem/composer.json#L1) — dependencias Composer.
- [ReservationSystem/include/conexion.php](ReservationSystem/include/conexion.php#L1) — conexión DB y uso de `.env`.
- [ReservationSystem/include/VerificacionSesion.php](ReservationSystem/include/VerificacionSesion.php#L1) — control de sesión y autenticación.
- [ReservationSystem/reserva/](ReservationSystem/reserva/), [ReservationSystem/admin/](ReservationSystem/admin/) — áreas de lógica de negocio.

Convenciones importantes
- El proyecto no usa framework moderno; son scripts PHP con `require`/`require_once`. Respeta rutas relativas al moverse entre carpetas.
- Autoload y dependencias gestionadas por Composer; no editar `ReservationSystem/vendor/` manualmente.
- Variables sensibles en `.env` y `docker-compose.yml` (no comitear secretos reales). `conexion.php` usa `vlucas/phpdotenv`.
- Cuidado con los scripts SQL: importarlos solo en entornos controlados.

Recomendaciones para agentes
- Priorizar enlaces sobre copia: enlaza a documentación existente en el repositorio en lugar de duplicarla.
- Antes de aplicar cambios que afectan dependencias, ejecutar `composer install` y comprobar `vendor/`.
- No modificar archivos en `vendor/`; proponer cambios a `composer.json` y dejar que el human los apruebe.
- Antes de ejecutar migraciones o `bd.sql`, pedir confirmación del usuario y hacer backup.
- Revisar uso de includes relativos; cuando muevas ficheros, actualizar rutas con pruebas locales.

Checks rápidos que un agente puede ejecutar
- `docker-compose config` para validar el compose.
- `composer validate` dentro de `ReservationSystem/`.
- `php -l <file>` para comprobar sintaxis PHP de archivos modificados.

Próximos pasos sugeridos
- Crear instrucciones específicas por área (`AGENTS-admin.md`, `AGENTS-reserva.md`) si el repo crece.
- Añadir pruebas automatizadas y un comando `make test` para facilitar verificaciones automáticas.

Si necesitas ampliar alguna sección o prefieres `.github/copilot-instructions.md` en lugar de este archivo, indícalo.

## Historial de cambios

- docs(agents): agregar AGENTS.md con instrucciones de montaje, comandos y recomendaciones para agentes
	- Fecha: 2026-07-09
	- Detalle: Archivo creado con resumen de comandos Docker/Composer, rutas críticas, convenciones y checks rápidos para agentes de IA.

