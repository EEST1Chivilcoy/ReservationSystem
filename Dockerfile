FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos de la aplicación
# Nota: Copiamos el contenido de la carpeta ReservationSystem a la raíz del servidor web
COPY ReservationSystem/ .

# Instalar dependencias de PHP
# --no-dev para producción, pero en este caso es un entorno "robusto" que podría ser dev.
# Usaremos por defecto instalación normal.
RUN composer install --no-interaction --optimize-autoloader

# Ajustar permisos para Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
