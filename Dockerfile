# Gunakan PHP 8.2 dengan Apache sebagai base image
FROM php:8.2-apache

# Set working directory di dalam container
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Hapus cache apt agar ukuran image lebih kecil
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions yang dibutuhkan Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer dari official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Aktifkan mod_rewrite Apache (penting untuk Laravel routing)
RUN a2enmod rewrite

# Konfigurasi virtual host Apache
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Salin seluruh isi Laravel ke container
COPY . /var/www/html

# Set permission untuk Laravel storage & cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install dependency Laravel
RUN composer install --no-dev --optimize-autoloader

# Install dependency frontend menggunakan npm
RUN npm install && npm run build
RUN rm -rf node_modules

# Expose port 80 untuk container
EXPOSE 80

# Jalankan Apache
CMD ["apache2-foreground"]
