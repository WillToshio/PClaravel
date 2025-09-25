CREATE DATABASE IF NOT EXISTS laravel;

-- Para MySQL 8+, use caching_sha2_password ou mysql_native_password se habilitado
CREATE USER IF NOT EXISTS 'laravel'@'%' IDENTIFIED WITH caching_sha2_password BY 'secret';

GRANT ALL PRIVILEGES ON laravel.* TO 'laravel'@'%';

FLUSH PRIVILEGES;