#!/bin/sh
set -e

# ==============================================================================
# Container Entrypoint for ManaCouture Production Image
# ==============================================================================

echo "==> Initializing Laravel application environment..."

# Ensure writable directories exist with correct permissions for www-data
mkdir -p \
    /var/www/html/storage/app/public \
    /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Create storage symlink if not already present
if [ ! -L /var/www/html/public/storage ]; then
    echo "==> Linking public storage directory..."
    php artisan storage:link --force || true
fi

# Optional: Run database migrations automatically if flagged
if [ "${AUTORUN_LARAVEL_MIGRATION:-false}" = "true" ]; then
    echo "==> Running database migrations..."
    php artisan migrate --force --graceful
fi

# Discover packages into manifest
echo "==> Discovering packages..."
php artisan package:discover --ansi || true

# Optional: Cache Laravel configuration, routes, and views for production speed
if [ "${AUTORUN_LARAVEL_OPTIMIZE:-true}" = "true" ] && [ -n "${APP_KEY}" ]; then
    echo "==> Caching Laravel configuration, routes, and views..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
fi

echo "==> Application ready. Launching process manager..."
exec "$@"
