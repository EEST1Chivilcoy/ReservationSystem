<img align="right" width="100" height="100" src="https://i.imgur.com/fSjgaVI.jpeg">

### ReservationSystem
###### Institución: Escuela de Educación Secundaria Técnica Nº1 "Mariano Moreno" de Chivilcoy

![Build Status](https://github.com/EEST1Chivilcoy/ReservationSystem/actions/workflows/main.yml/badge.svg)
![Web Status](https://img.shields.io/website-up-down-green-red/http/reserva.great-site.net)
![Issue Open](https://img.shields.io/github/issues/EEST1Chivilcoy/ReservationSystem.svg)

---

### 📖 Descripción

Este repositorio contiene el código de una aplicación web diseñada para la gestión y reserva de espacios escolares (salón de audiovisuales, comedor y salón de actos).

**Funcionalidades principales:**

* 🔐 **Control de Acceso:**
    * **Administrador:** Gestión total de reservas y usuarios.
    * **Usuario:** Capacidad de crear y gestionar sus propias reservas.
* 📺 **Gestión de Espacios:** Reserva de salones específicos para proyecciones o eventos.
* 📽️ **Solicitud de Equipamiento:** Opción para incluir la solicitud de proyector especificando el lugar de uso.

---

### 🚀 Guía de Instalación Manual

Sigue estos pasos para desplegar el proyecto en un entorno local clásico (AMP stack).

#### 1. Clonar el repositorio
```sh
git clone https://github.com/EEST1Chivilcoy/ReservationSystem.git
cd ReservationSystem

```

#### 2. Configuración de Entorno (.env)

Crea un archivo llamado `.env` en la raíz del proyecto y define tus credenciales de base de datos:

```env
DB_HOST=localhost
DB_USER=tu_usuario
DB_PASS=tu_clave
DB_NAME=tu_basededatos

```

#### 3. Instalación de Dependencias

Asegúrate de tener [Composer](https://getcomposer.org/download/) instalado y ejecuta:

```sh
composer install

```

> Esto generará la carpeta `vendor/` necesaria para el manejo de variables de entorno con `vlucas/phpdotenv`.

#### 4. Base de Datos

Importa el archivo `bd.sql` (ubicado en la raíz) en tu gestor de base de datos (phpMyAdmin, Workbench, etc.) para generar las tablas requeridas.

---

### 🐳 Despliegue con Docker (Recomendado)

Es la forma más rápida de probar la aplicación sin configurar un servidor manual.

1. Asegúrate de tener **Docker** y **Docker Compose** instalados.
2. Ejecuta en la terminal:
```sh
docker-compose up -d --build

```


3. Accede a **[http://localhost](https://www.google.com/search?q=http://localhost)**.

> **Nota:** La configuración de Docker ya incluye las variables de entorno necesarias y la autoconfiguración de la base de datos.

---

### 👥 Equipo del Proyecto

| Rol | Responsable |
| --- | --- |
| **Idea y concepto original** | Sergio Caffaro (Profesor) |
| **Creador de la v2.0** | Bernardo G. Erramuspe |
| **Desarrollo de la v1.0** | Estudiantes de 6to año (2023-2024) |
| **Diseño UI/UX (v1.0)** | Estudiantes de 6to año (2023) |

---

<div align="center">

[<img alt="Deployed with FTP Deploy Action" src="https://img.shields.io/badge/Deployed With-FTP DEPLOY ACTION-%3CCOLOR%3E?style=for-the-badge&color=0077b6">](https://github.com/SamKirkland/FTP-Deploy-Action)

</div>
