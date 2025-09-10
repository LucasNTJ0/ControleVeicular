set -e

echo "Ajustando permissões de storage e cache..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Permissões ajustadas, iniciando PHP-FPM..."
exec "$@"