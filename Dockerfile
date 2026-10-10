# syntax=docker/dockerfile:1

# ---------- Stage 1: build (composer + node) ----------
FROM dunglas/frankenphp:1-php8.4 AS build
WORKDIR /app

RUN apt-get update \
 && apt-get install -y --no-install-recommends curl git unzip ca-certificates \
 && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
 && apt-get install -y --no-install-recommends nodejs \
 && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions pdo_mysql intl zip bcmath opcache gd
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction
# Wayfinder butuh PHP saat build, makanya build frontend di stage yang ada PHP-nya
RUN npm ci && npm run build && rm -rf node_modules

# ---------- Stage 2: runtime ----------
FROM dunglas/frankenphp:1-php8.4
WORKDIR /app

RUN install-php-extensions pdo_mysql intl zip bcmath opcache gd \
 && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --from=build /app /app
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/Caddyfile /etc/frankenphp/Caddyfile
COPY --chmod=755 docker/entrypoint.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]