# ================================
# 1º estágio: frontend (Node + Tailwind/Vite)
# ================================
FROM node:18 AS frontend

WORKDIR /app

# Copia apenas package.json e package-lock.json
COPY package*.json ./

# Instala dependências do frontend
RUN npm config set fetch-retry-maxtimeout 120000 \
    && npm config set fetch-retry-mintimeout 20000 \
    && npm install


# Copia todo o restante do projeto (exceto node_modules, ignorado pelo .dockerignore)
COPY . .

# Build dos assets do Tailwind/Vite
RUN npm run build


# ================================
# 2º estágio: backend (PHP + Composer)
# ================================
FROM php:8.3-fpm

# Argumento para grupo do host
ARG WWWGROUP=1000

# Cria usuário sail (opcional, mas recomendado)
RUN groupadd -g ${WWWGROUP:-1000} sail || true \
    && useradd -ms /bin/bash -g sail -u 1337 sail

# Instala extensões do PHP
RUN docker-php-ext-install pdo pdo_mysql

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia arquivos do backend
COPY . .

# Copia assets do frontend
COPY --from=frontend /app/public/build ./public/build

# Instala dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html /var/www/html/storage

CMD ["php-fpm"]