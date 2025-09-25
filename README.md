# 🍽️ Menu Interativo para Restaurante - Laravel + Docker

## 📋 Sobre o Projeto
Projeto desenvolvido para criar um **cardápio interativo digital** para restaurantes, utilizando **Laravel e Docker**.  
O objetivo é substituir cardápios físicos por uma solução **digital, moderna, intuitiva e fácil de atualizar**.

---

## 🎯 Motivação
- Aumentar conhecimento em desenvolvimento full-stack  
- Treinar habilidades com Laravel, Docker e MySQL  
- Criar solução real para demanda familiar (restaurante)  
- Desenvolver portfólio com projeto prático  

---

## 🚀 Tecnologias Utilizadas
- **Backend:** Laravel 10+  
- **Frontend:** Blade, JavaScript, Tailwind CSS  
- **Database:** MySQL 8.0  
- **Containerização:** Docker + Docker Compose  
- **Servidor:** Nginx + PHP 8.2-FPM  

---

## 📁 Estrutura do Projeto
```
nome_do_arquivo/
├── docker/
│   ├── nginx/
│   │   └── default.conf
│   ├── php/
│   │   └── Dockerfile
│   └── mysql/
│       └── init.sql
├── src/
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   └── ...
├── docker-compose.yml
├── .env
└── README.md
```

---

## ✅ Checklist de Desenvolvimento

### 🔧 Fase 1: Setup do Ambiente
- [x] Configurar Docker e Docker Compose  
- [x] Criar estrutura de diretórios  
- [x] Configurar Dockerfile para PHP 8.2-FPM  
- [x] Configurar Nginx e MySQL  
- [x] Criar `docker-compose.yml`  

### 🐘 Fase 2: Banco de Dados
- [ ] Criar diagrama ER  
- [ ] Definir tabelas: categorias, produtos, pedidos, etc.  
- [ ] Configurar container MySQL  
- [ ] Criar script de inicialização  

### 🔨 Fase 3: Setup Laravel
- [x] Instalar Laravel via Composer  
- [x] Configurar `.env` para Docker  
- [x] Testar conexão com MySQL  
- [x] Gerar `APP_KEY`  
- [ ] Criar migrations, models e relações  

### 🎨 Fase 4: Desenvolvimento Backend
- CRUD de Categorias e Produtos  
- Sistema de Pedidos com status  
- Controllers, Models e Routes organizadas  

### 💻 Fase 5: Desenvolvimento Frontend
- Layout responsivo com Blade + Tailwind  
- Páginas do cardápio (lista de categorias e produtos)  
- Área administrativa (dashboard)  

### ⚡ Fase 6: Funcionalidades Avançadas
- Sistema de pedidos em tempo real (WebSockets)  
- Notificações para cozinha  
- API REST para integração com mobile  
- Upload de imagens, pesquisa e filtros  

### 🧪 Fase 7: Testes
- Testes unitários (models, controllers, relações)  
- Testes de integração (fluxo completo e API)  

### 🚀 Fase 8: Deploy e Produção
- Cache de configs  
- Otimização de imagens  
- SSL configurado  
- Backup automático  

---

## 🐳 Comandos Docker Essenciais
```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Rebuildar imagem
docker-compose build --no-cache app

# Ver logs
docker-compose logs app
docker-compose logs nginx
docker-compose logs db

# Executar comandos no container
docker-compose exec app php artisan migrate
docker-compose exec app composer install
docker-compose exec app bash
```

---

## 🗃️ Estrutura do Banco de Dados
**Tabelas Principais:**
- `categories`: Categorias de produtos (Entradas, Pratos, Sobremesas)  
- `products`: Produtos do cardápio  
- `orders`: Pedidos dos clientes  
- `order_items`: Itens de cada pedido  
- `users`: Administradores do sistema  

---

## 🎯 Funcionalidades Planejadas

### Para Clientes
- Visualizar cardápio por categorias  
- Pesquisar produtos  
- Fazer pedidos via QR Code  
- Acompanhar status do pedido  
- Ver detalhes dos produtos  

### Para Administração
- Gerenciar categorias e produtos  
- Visualizar pedidos em tempo real  
- Atualizar status dos pedidos  
- Estatísticas de vendas  
- Gerenciar horário de funcionamento  

---

## 🔧 Desenvolvimento Local

### Pré-requisitos
- Docker 20.10+  
- Docker Compose 2.0+  
- Git  

### Primeira Configuração
```bash
# Clone o projeto
git clone [url-do-projeto]
cd nome_do_arquivo

# Configure o .env
cp .env.example .env

# Inicie os containers
docker-compose up -d --build

# Execute migrações
docker-compose exec app php artisan migrate

# Na primeira vez gerando o container gere chave da aplicação
docker-compose exec app php artisan key:generate --show

# copie e cole no .env na parte do APP_KEY 

# Acesse a aplicação
http://localhost:8080
```

---

## 🤝 Contribuição
Este é um projeto de aprendizado.  
Sugestões e contribuições são bem-vindas!

---

## 📞 Suporte
Em caso de dúvidas ou problemas:
1. Verificar logs dos containers  
2. Consultar documentação do Laravel  
3. Verificar issues no GitHub  

---

✍️ *Este README será atualizado conforme o projeto evolui!*
