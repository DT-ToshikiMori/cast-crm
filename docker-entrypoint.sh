#!/bin/bash
set -e

php artisan config:cache
php artisan route:cache
php artisan migrate --force

echo "Starting server on port ${PORT:-8080}..."
exec php -S 0.0.0.0:"${PORT:-8080}" -t public
