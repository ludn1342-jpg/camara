# Usamos una imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instalamos las extensiones necesarias para PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Habilitamos el módulo de reescritura de Apache (útil si usas rutas amigables)
RUN a2enmod rewrite

# Copiamos TODO el proyecto a la carpeta web de Apache.
# Esto incluye los HTML de la raíz y la carpeta /api
COPY . /var/www/html/

# Ajustamos los permisos para que Apache pueda leer los archivos
RUN chown -R www-data:www-data /var/www/html/

# Configuramos Apache para que escuche en el puerto que Render le asigne dinámicamente
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Exponemos el puerto (Render maneja esto automáticamente)
EXPOSE 80
