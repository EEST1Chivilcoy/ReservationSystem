# Instrucciones para agentes (AGENTS.md)

Ayuda a un agente a montar, verificar y modificar el repo sin romper dependencias ni datos.

## Estructura y arquitectura (no obvio)
- Todo el código de la app vive en `ReservationSystem/`. Docker (`Dockerfile`) y CI copian/despliegan el **contenido** de esa carpeta como raíz web (`/var/www/html`, `htdocs/`). No confundir la raíz del repo con la raíz web.
- PHP plano sin framework: scripts que se enlazan con `include`/`require`. No hay router ni namespaces de app en uso.
- `composer.json` declara autoload PSR-4 a `src/`, pero esa carpeta **no existe**. Solo se autocargan las librerías de vendor (`vlucas/phpdotenv`, `tecnickcom/tcpdf`). No esperes que código bajo el namespace `Eest1chivilcoy\ReservationSystem\` se autocarga; crea `src/` si lo necesitas.
- La tabla principal de reservas se llama literalmente `tabla` (BD `ReservationSystem`).

## Comandos
- Entorno completo: `docker-compose up -d --build` (web + MySQL 8.0). Web en http://localhost.
- Local sin Docker: en `ReservationSystem/` ejecutar `composer install` y luego `php -S localhost:8000 -t .` (desde `ReservationSystem/`, no la raíz del repo).
- **No hay tests automatizados.** Verificación = `php -l <archivo>` (lint por archivo), `composer validate` (en `ReservationSystem/`) y `docker-compose config`.
- CI (push a `main`) despliega por FTP a InfinityFree con `composer install --no-dev`; no corre migraciones.

## Base de datos
- `bd.sql` se monta como init automático en Docker (`/docker-entrypoint-initdb.d/init.sql`) y crea la BD. Importar en local: `mysql -u <user> -p ReservationSystem < bd.sql` desde la raíz del repo.
- `upgrade_v2.2.sql`, `upgrade_v2.3.sql`, `downgrade_v2.sql` **no** se ejecutan solos; aplicarlos manualmente y en orden. Pedir confirmación y backup antes de correrlos.
- `conexion.php` lee `.env` desde `ReservationSystem/.env` (**no** la raíz del repo, pese a lo que dice el README). En Docker usa variables de entorno (`DB_HOST=db`, `DB_USER`, `DB_PASS`, `DB_NAME`).

## Convenciones y gotchas
- Includes relativos según la carpeta del script: raíz usa `include('include/conexion.php')`; subcarpetas (`reserva/`, `admin/`) usan `include('../include/conexion.php')`. Al mover archivos, ajusta estas rutas y pruébalas.
- Sesión: `include/VerificacionSesion.php` (logueado) y `include/VerificacionAdmin.php` (admin) llaman `session_start()` y redirigen. Claves usadas: `loggedIn`, `EsAdmin`, `usuario_id`, `nombreyapellido`, `foto_perfil`.
- `index.php` abre y cierra la conexión BD dos veces (el bloque admin re-incluye `conexion.php`). Mantener ese patrón al editar.
- Timezone fijo `America/Argentina/Buenos_Aires` en `index.php`.
- `.htaccess` fuerza HTTPS excepto en localhost y bloquea el acceso web a `.env`, `composer.json`, `composer.lock`, `README.md`. No planees servir esos archivos.
- Secrets en `.env` (ignorado por git) y en GitHub Secrets para CI (`FTP_HOST`, `FTP_USER`, `ftp_password`). No commitear credenciales reales.
- No editar `ReservationSystem/vendor/`; los cambios de dependencias van en `composer.json`.
