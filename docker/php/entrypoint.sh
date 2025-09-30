#!/bin/bash
set -e

# Caminho para o .env usado dentro do container
ENV_FILE="/var/www/.env"

# Instalar dependências se não existir
if [ ! -d "/var/www/vendor" ]; then
    echo "Vendor não encontrado. Instalando dependências..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi



# Se o .env não existir, copiar do template baseado em APP_ENV
if [ ! -f "$ENV_FILE" ]; then
    echo "Copiando .env template para $ENV_FILE"
    cp "/var/www/.env.${APP_ENV}" "$ENV_FILE"
fi

# Gerar APP_KEY se estiver vazia
if ! grep -q "APP_KEY=base64:" "$ENV_FILE"; then
    echo "APP_KEY não encontrada. Gerando nova chave..."
    NEW_KEY=$(php /var/www/artisan key:generate --show)
    echo "APP_KEY=$NEW_KEY";
    echo "Nova APP_KEY gerada e adicionada ao .env. Copie para o arquivo correspondente (.env.dev, .env.stag ou .env.prod) para manter fixa."
fi

echo "Limpando cache do Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache

# Esperar MySQL ficar pronto
until php -r "try { new PDO('mysql:host=${DB_HOST};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); } catch (Exception \$e) { echo 'Não conectado'; exit(1); }" >/dev/null 2>&1; do
  echo "Aguardando MySQL..."
  sleep 2
done

# Rodar migrations automaticamente (opcional em dev/stag)
if [ "$APP_ENV" != "production" ]; then
    echo "MySQL pronto! Rodando migrations..."
    php artisan migrate --force
    php artisan db:seed
fi

if [ ! -f "/var/www/config/sanctum.php" ]; then
    php artisan vendor:publish --provider="Laravel\\Sanctum\\SanctumServiceProvider" --force
fi

# Garantir permissões corretas
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Executar o comando padrão do container (php-fpm)
exec "$@"
