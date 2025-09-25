FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git unzip zip curl libpq-dev libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip mbstring

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copia o código Laravel para dentro da imagem
COPY ./src /var/www

# Instala dependências do Laravel
# Dá permissão para o Laravel rodar futuramente
RUN composer install \
    && chown -R www-data:www-data /var/www

# Copiar entrypoint
COPY ./docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]