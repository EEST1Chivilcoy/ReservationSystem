<img align="right" width="100" height="100" src="https://i.imgur.com/fSjgaVI.jpeg">

### ReservationSystem
###### Institución: Escuela de Educación Secundaria Técnica Nº1 "Mariano Moreno" de Chivilcoy

---

### Estado del Proyecto

![Build Status](https://github.com/EEST1Chivilcoy/ReservationSystem/actions/workflows/main.yml/badge.svg)
![Web Status](https://img.shields.io/website-up-down-green-red/http/reserva.great-site.net)
![Issue Open](https://img.shields.io/github/issues/EEST1Chivilcoy/ReservationSystem.svg)

---

### Equipo del Proyecto

- **Idea y concepto original:** Sergio Caffaro (Profesor)
- **Creador de la segunda versión:** Bernardo G. Erramuspe
- **Desarrollo de la primera versión:** Estudiantes de 6to año (2023-2024)
- **Diseño de la primera interfaz y levantamiento de requerimientos:** Estudiantes de 6to año (2023)

---

### Descripción

Este repositorio contiene el código de una aplicación web para reservar el salón de audiovisuales, el comedor o el salón de actos. El sistema ofrece las siguientes funcionalidades:

- **Inicio de sesión con cuentas normales y de administrador:**
  - **Cuentas normales**: Permiten realizar reservas.
  - **Cuentas de administrador**: Permiten modificar reservas y gestionar los datos de otros usuarios.
- **Reserva de espacios para proyecciones**: Los salones pueden ser reservados para visualizar contenido en un proyector o televisor (en el caso del comedor y el salón de audiovisuales).
- **Solicitud del proyector**: Los usuarios pueden solicitar el proyector y especificar el salón de la escuela donde desean usarlo.

---

### **Configuración**

Para que el proyecto funcione correctamente, es **imprescindible** crear un archivo `.env` en la raíz del repositorio con el siguiente contenido:

```env
DB_HOST=localhost
DB_USER=tu_usuario
DB_PASS=tu_clave
DB_NAME=tu_basededatos
```

Además, asegúrate de instalar las dependencias necesarias con Composer:

```sh
composer install
```

Esto generará la carpeta `vendor/` con las librerías necesarias, incluyendo `vlucas/phpdotenv` para manejar el archivo `.env`.

---

### **Instrucciones de uso**

1. **Clona el repositorio** a tu máquina local:
   ```sh
   git clone https://github.com/EEST1Chivilcoy/ReservationSystem.git
   ```
2. **Crea el archivo `.env`** en la raíz del proyecto con tus credenciales de la base de datos.
3. **Instala Composer** si aún no lo tienes:
   - Descárgalo desde [getcomposer.org](https://getcomposer.org/download/)
   - Verifica la instalación con:
     ```sh
     composer --version
     ```
4. **Ejecuta `composer install`** para instalar las dependencias y asegurarte de que el autoload funcione correctamente.
5. **Importa la base de datos** utilizando el archivo `bd.sql` ubicado en la raíz del repositorio. Este archivo contiene todos los comandos necesarios para crear la base de datos MySQL requerida.
6. **Sube el proyecto** a tu servidor web y verifica los permisos.
7. **Accede a la aplicación web** y utiliza las funcionalidades según el tipo de cuenta (normal o administrador).

---

[<img alt="Deployed with FTP Deploy Action" src="https://img.shields.io/badge/Deployed With-FTP DEPLOY ACTION-%3CCOLOR%3E?style=for-the-badge&color=0077b6">](https://github.com/SamKirkland/FTP-Deploy-Action)

---

### 🐳 Despliegue con Docker

¡También puedes levantar el proyecto usando Docker! Es la forma más fácil de empezar.

1.  Asegúrate de tener Docker y Docker Compose instalados.
2.  Ejecuta el siguiente comando en la raíz del proyecto:
    ```sh
    docker-compose up -d --build
    ```
3.  Accede a la aplicación en [http://localhost](http://localhost).
4.  La base de datos se inicializará automáticamente con los datos necesarios.

**Nota:** La configuración de Docker ya incluye las variables de entorno necesarias para conectar la aplicación con la base de datos dentro del contenedor.

