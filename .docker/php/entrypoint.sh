#!/bin/sh
set -e

# Ensure necessary directories exist and correct permissions
mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

# If composer not installed vendor, try installing dependencies (non-interactive)
if [ ! -d /var/www/html/vendor ]; then
  echo "Installing composer dependencies..."
  cd /var/www/html
  composer install --no-interaction --prefer-dist --optimize-autoloader || true
fi

# If APP_KEY is missing, try to generate it (only if artisan exists)
if [ -f /var/www/html/artisan ] && [ -z "${APP_KEY:-}" ]; then
  cd /var/www/html
  php artisan key:generate --force || true
fi

# Execute the passed command
exec "$@"

