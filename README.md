<img align="right" width="100" height="100" src="https://i.imgur.com/fSjgaVI.jpeg">

### ReservationSystem
###### Institución: Escuela Técnica N°1 Chivilcoy

---


### Descripción

Este repositorio contiene el código de una aplicación web para reservar el salón de audiovisuales, el comedor o el salón de actos. El sistema ofrece las siguientes funcionalidades:

- **Inicio de sesión con cuentas normales y de administrador:**
  - **Cuentas normales**: Permiten realizar reservas.
  - **Cuentas de administrador**: Permiten modificar reservas y gestionar los datos de otros usuarios.
- **Reserva de espacios para proyecciones**: Los salones pueden ser reservados para visualizar contenido en un proyector o televisor (en el caso del comedor y el salón de audiovisuales).
- **Solicitud del proyector**: Los usuarios pueden solicitar el proyector y especificar el salón de la escuela donde desean usarlo.

### Configuración

Para que el proyecto funcione correctamente, es **imprescindible** crear un archivo llamado `config_database.php` en el directorio `include` con el siguiente contenido:

```php
<?php
return [
    'db' => [
        'usuario' => 'tu_usuario',
        'clave' => 'tu_clave',
        'servidor' => 'tu_servidor',
        'basededatos' => 'tu_basededatos',
    ],
];
?>
```

### Instrucciones de uso

1. Clona el repositorio a tu máquina local.
2. Crea el archivo `config_database.php` en el directorio `include` con las credenciales de tu base de datos.
3. Importa la base de datos utilizando el archivo `bd.sql` ubicado en la raíz del repositorio. Este archivo contiene todos los comandos necesarios para crear la base de datos MySQL requerida.
4. Sube el proyecto a tu servidor web y asegúrate de que los permisos sean correctos.
5. Accede a la aplicación web y utiliza las funcionalidades según el tipo de cuenta (normal o administrador).

---

### Estado del Proyecto

![Web Status](https://img.shields.io/website-up-down-green-red/http/reserva.000.pe)
![Issue Open](https://img.shields.io/github/issues/EEST1Chivilcoy/ReservationSystem.svg)

---
