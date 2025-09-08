# Imagem oficial PHP-FPM
FROM php:8.3-fpm

# Argumento para o grupo
ARG WWWGROUP=1000

# Criar usuário 'sail'
RUN groupadd -g ${WWWGROUP:-1000} sail || true \
    && useradd -ms /bin/bash -g sail -u 1337 sail

# Instalar dependências do Laravel
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg-dev libfreetype6-dev zip curl \
    && docker-php-ext-install pdo pdo_mysql gd bcmath

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar apenas os arquivos de dependências primeiro (cache melhorado)
COPY composer.json composer.lock ./

# Instalar dependências do Laravel (sem dev, otimizado)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Copiar restante do código Laravel
COPY . .

# Ajustar permissões para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# PHP-FPM como comando padrão
CMD ["php-fpm"]
