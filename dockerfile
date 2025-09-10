# ================================
# 1º estágio: frontend (Node + Tailwind/Vite)
# ================================
FROM node:18 AS frontend

WORKDIR /app

# Copia apenas package.json e package-lock.json
COPY package*.json ./

# Instala dependências do frontend de forma reprodutível
RUN npm ci

# Copia todo o restante do projeto (exceto o que está no .dockerignore)
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

# Instala extensões do PHP e dependências extras
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia arquivos do backend
COPY . .

# Copia assets do frontend
COPY --from=frontend /app/public/build ./public/build

# Instala dependências PHP (somente produção)
RUN composer install --no-dev --optimize-autoloader


# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html /var/www/html/storage \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configura o PHP-FPM para escutar em todas interfaces
RUN sed -i 's/listen = .*/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Inicia o PHP-FPM em foreground (modo recomendado para Docker)
CMD ["php-fpm", "-F"]
