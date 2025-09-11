#!/bin/bash
set -e

echo "Ajustando permissões de storage e bootstrap/cache..."

# Cria pastas caso não existam
mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache

# Ajusta proprietário e permissões
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Permissões ajustadas, iniciando PHP-FPM..."
exec "$@"
