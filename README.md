<img align="right" width="100" height="100" src="https://i.imgur.com/fSjgaVI.jpeg">

### ReservationSystem
###### Institución: Escuela Técnica N°1 Chivilcoy

---

## Español

### Descripción

Este repositorio contiene el código de una página web para la reserva del salón de audiovisuales, comedor o salón de actos en la Escuela Técnica Industrial Nº1 "Mariano Moreno", ubicada en Chivilcoy. El sistema permite:

- **Inicio de sesión con cuentas normales y de administrador.**
  - **Cuentas normales**: Los usuarios pueden realizar reservas.
  - **Cuentas de administrador**: Los administradores pueden modificar reservas y gestionar los datos de otros usuarios.
- **Reservar espacios para proyecciones:** Los salones se pueden reservar para ver contenido en un proyector o televisor (en el caso del comedor y audiovisuales).
- **Solicitar el proyector:** Los usuarios pueden pedir el proyector y especificar cualquier salón de la escuela para su uso.

### Configuración

Para asegurar el correcto funcionamiento del proyecto, crea un archivo llamado `config_database.php` en el directorio `include` con el siguiente código:

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
3. Importa la base de datos utilizando el archivo `bd.sql` que se encuentra en la raíz del repositorio. Este archivo contiene todos los comandos necesarios para crear la base de datos MySQL requerida.
4. Sube el proyecto a tu servidor web y asegúrate de que los permisos sean correctos.
5. Accede a la página web y utiliza las funcionalidades según el tipo de cuenta (normal o administrador).

---

## English

### Description

This repository contains the code for a web page designed to reserve the audiovisual room, dining hall, or auditorium at Escuela Técnica Industrial Nº1 "Mariano Moreno" located in Chivilcoy. The system includes:

- **Login functionality with normal and admin accounts.**
  - **Normal accounts**: Users can make reservations.
  - **Admin accounts**: Administrators can modify reservations and manage other users' data.
- **Reserve spaces for viewing content:** Rooms can be reserved to watch content using a projector or TV (in the case of the dining hall and audiovisual room).
- **Request the projector:** Users can request the projector and specify any room in the school for its use.

### Configuration

To ensure the project functions correctly, create a file named `config_database.php` in the `include` directory with the following code:

```php
<?php
return [
    'db' => [
        'usuario' => 'your_user',
        'clave' => 'your_password',
        'servidor' => 'your_server',
        'basededatos' => 'your_database',
    ],
];
?>
```

### Usage Instructions

1. Clone the repository to your local machine.
2. Create the `config_database.php` file in the `include` directory with your database credentials.
3. Import the database using the `bd.sql` file located at the root of the repository. This file contains all the necessary commands to create the required MySQL database.
4. Upload the project to your web server and ensure the permissions are correct.
5. Access the web page and use the functionalities according to the account type (normal or admin).
