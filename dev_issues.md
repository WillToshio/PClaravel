# Guia de Problemas e Soluções: Laravel + Docker + MySQL + Nginx

## 1️⃣ Laravel APP_KEY e PHP incompatível

**Problema:** Não é possível gerar a APP_KEY porque o PHP da máquina local não é compatível com Laravel 12.

**Solução:**

- Criar um container com PHP compatível.
- Copiar o Laravel para dentro do container ou mapear volume.
- Acessar o bash do container:

```bash
docker-compose exec php bash
```

- Rodar o comando:

```bash
php artisan key:generate --show
```

- Copiar a chave gerada e colar no `.env` do Laravel:

```dotenv
APP_KEY=base64:...
```

## 2️⃣ Variáveis não carregam no Docker Compose

**Problema:** APP_PORT is not set, DB_CONNECTION_MYSQL not set.

**Causa:** O `.env` do Laravel não suporta interpolação (`${VAR}`).

**Solução:**

- Criar um `.env` na raiz do projeto apenas para Docker Compose:

```dotenv
APP_PORT=8080
MYSQL_PORT=3306
MYSQL_DATABASE=laravel
MYSQL_USER=laravel
MYSQL_PASSWORD=secret
MYSQL_ROOT_PASSWORD=root
```

- Manter `.env` do Laravel separado com valores fixos:

```dotenv
APP_URL=http://localhost:8080
DB_CONNECTION=mysql
```

- **Boas práticas:** Criar `.env.dev`, `.env.stag` e `.env.prod` para diferenciar ambientes e evitar subir `.env` com dados sensíveis no repositório.

## 3️⃣ MySQL 8 não conecta com Laravel

**Problema:** Plugin 'mysql_native_password' is not loaded ou falha de autenticação.

**Solução:**

- No `init.sql` do MySQL:

```sql
CREATE DATABASE IF NOT EXISTS laravel;
CREATE USER IF NOT EXISTS 'laravel'@'%' IDENTIFIED WITH mysql_native_password BY 'secret';
GRANT ALL PRIVILEGES ON laravel.* TO 'laravel'@'%';
FLUSH PRIVILEGES;
```

- Garantir que `DB_HOST=mysql` no `.env` do Laravel (nome do serviço no Compose).

## 4️⃣ Container morre com exit status 137

**Problema:** Container é finalizado abruptamente pelo sistema (SIGKILL).

**Causa:** Falta de memória ou excesso de processos dentro do container (PHP-FPM, MySQL).

**Solução:**

- Aumentar memória disponível para Docker Desktop.
- Reduzir número de workers de PHP-FPM ou MySQL.
- Evitar rodar `php artisan serve` dentro do container; usar Nginx + PHP-FPM.

## 5️⃣ Laravel não inicia sem banco de dados

**Problema:** `no such table: sessions` ou erro ao rodar migrations.

**Solução:**

- Para desenvolvimento rápido, usar SQLite em memória no `.env` do Laravel:

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

- Para MySQL, garantir que o container está pronto antes de rodar migrations (ver abaixo o uso do `entrypoint.sh`).

## 6️⃣ Entrypoint.sh para inicialização confiável

**Problema:** Laravel tenta rodar antes do MySQL estar pronto ou APP_KEY não gerada.

**Solução:** Criar `entrypoint.sh` no container PHP:

```bash
#!/bin/bash
set -e

ENV_FILE="/var/www/.env"

# Copia template do .env se não existir
if [ ! -f "$ENV_FILE" ]; then
    cp "/var/www/.env.${APP_ENV}" "$ENV_FILE"
fi

# Gera APP_KEY se estiver vazia
if ! grep -q "APP_KEY=base64:" "$ENV_FILE"; then
    NEW_KEY=$(php /var/www/artisan key:generate --show)
    echo "APP_KEY=$NEW_KEY" >> "$ENV_FILE"
fi

# Espera MySQL estar pronto
until php -r "try { new PDO('mysql:host=mysql;dbname=laravel', 'laravel', 'secret'); } catch (Exception \$e) {}" >/dev/null 2>&1; do
  echo "Aguardando MySQL..."
  sleep 2
done

# Rodar migrations em dev/staging
if [ "$APP_ENV" != "production" ]; then
    php /var/www/artisan migrate --force
fi

# Garantir permissões
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Executar comando padrão
exec "$@"
```

- Inclua no Dockerfile:

```dockerfile
COPY ./docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
```

## 7️⃣ Nginx não encontra PHP-FPM

**Problema:** 502 Bad Gateway ou arquivos não são servidos.

**Solução:**

- Verificar `fastcgi_pass` no `default.conf` do Nginx:

```nginx
location ~ \.php$ {
    fastcgi_pass php:9000;
    fastcgi_index index.php;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}
```

- Mapear volumes corretamente:

```yaml
volumes:
  - ./src:/var/www
  - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
```

## 8️⃣ .dockerignore

**Problema:** Build pesado ou expor arquivos sensíveis.

**Solução:**

- Criar `.dockerignore` na raiz:

```
.git
.github
node_modules
vendor
.env
.env.*
```

- Criar `.dockerignore` dentro de `/src` se necessário:

```
.env
.env.local
/storage
```

- Isso reduz o build, protege dados sensíveis e evita enviar volumes desnecessários para a imagem.

## 9️⃣ Boas práticas gerais

- Separar `.env` do Laravel e do Docker Compose.
- Criar `.env.dev`, `.env.stag` e `.env.prod` para versionar variáveis por ambiente.
- Usar `entrypoint.sh` para inicializar APP_KEY, migrations e checar dependências.
- Inicializar MySQL com `init.sql` para criar usuário e banco corretos.
- Testar sem banco usando SQLite antes de conectar MySQL.
- Documentar cada erro e solução em um arquivo dedicado (`dev_issues.md`).

