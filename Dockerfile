FROM php:7.4-apache

# Instalar extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli zip

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Copiar los archivos de la aplicación
COPY . /var/www/html/

# Copiar configuración de Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html

# Exponer puerto 80
EXPOSE 80