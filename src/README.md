# Laravel - Passos para rodar o projeto após o clone

Este projeto foi clonado do diretório oficial do laravel: [https://github.com/laravel/laravel.git](https://github.com/laravel/laravel.git)

caso queira clonar siga os seguintes passos: 
## passo 1
- clone o projeto de onde desejar:
```bash 
git clone https://github.com/laravel/laravel.git 
```
- caso deseja clonar na raiz, adicione . no final ex:
```bash 
git clone https://github.com/laravel/laravel.git . 
```
## passo 2
- altere o remote origin: *obrigatório*
```bash 
git git remote set-url origin link_do_seu_repositorio
```
- caso queira saber se alterou corretamente, use o comando 

```bash 
git remote -v 
#ele mostrará o link que será feito o fetch e o push, no caso desse projeto:
origin  https://github.com/WillToshio/PClaravel.git (fetch)
origin  https://github.com/WillToshio/PClaravel.git (push)
```

## passo 3
- entrar na pasta do projeto (se precisar)
```bash
cd nome_da_pasta
```

## passo 4
- Instalar dependências do Composer
```bash
composer install
```

## passo 5
- criar arquivo `.env`
```bash
cp .env.example .env
```

## passo 6
- gerar chave da aplicação
```bash
php artisan key:generate
```

## passo 7
- configurar banco de dados (se precisar)
Edite o arquivo `.env` e ajuste os dados:

#### sqlite
- segundo a documentação, o sqlite é fácil de configurar.
crie o arquivo sqlite usando o comando: 
```bash
touch database/database.sqlite
```
depois de criado:
```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```
por padrão a chave estrangeira é abilitada para conexões sqlite. se quiser desabilitar é só adicionar
```dotenv
DB_FOREIGN_KEYS=false
```

#### mysql
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

#### SQLServe
```dotenv
DB_CONNECTION=sqlsrv
DB_HOST=url_do_banco
DB_PORT=
DB_DATABASE=laravel
DB_USERNAME=user
DB_PASSWORD=pass
```

## 6. Rodar as migrations
```bash
php artisan migrate
```

## 7. Subir o servidor embutido do Laravel
```bash
php artisan serve
```
Acesse em: [http://localhost:8000](http://localhost:8000)
--- 
caso prefira usar o localhost sem precisar rodar o artisan serve use o link: [http://localhost/$nome_do_arquivo/public](http://localhost/$nome_do_arquivo/public)
