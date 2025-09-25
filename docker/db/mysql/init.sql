CREATE DATABASE IF NOT EXISTS laravel;

CREATE USER IF NOT EXISTS 'laravel'@'%' IDENTIFIED WITH mysql_native_password BY 'secret';

GRANT ALL PRIVILEGES ON laravel.* TO 'laravel'@'%';

FLUSH PRIVILEGES;