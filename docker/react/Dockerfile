FROM node:20-alpine

# Diretório de trabalho dentro do container
WORKDIR /app

# Copia package.json e package-lock.json primeiro (cache eficiente)
COPY ./app/package*.json ./

# Instala dependências
RUN npm install

# Copia o resto do código
COPY . .

# Expõe a porta padrão do React
EXPOSE 3000

# Comando para rodar o servidor React com hot reload
CMD ["npm", "start"]
