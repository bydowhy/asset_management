#!/bin/sh
set -e
cd /app

mkdir -p storage/app/private storage/logs \
         storage/framework/cache/data storage/framework/sessions storage/framework/views \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

exec frankenphp run --config /etc/frankenphp/Caddyfile --adapter caddyfile