# ==============================================================================
# Production-Ready Multi-Stage Dockerfile for Laravel E-Commerce (ManaCouture)
# 
# Stages:
#   1. frontend-builder : Vite & Tailwind CSS compilation
#   2. composer-builder : Production PHP dependencies & optimized classmap
#   3. production       : High-performance, lean Alpine runtime (Nginx + PHP-FPM)
# ==============================================================================

ARG PHP_VERSION=8.3

# ------------------------------------------------------------------------------
# Stage 1: Build Frontend Assets
# ------------------------------------------------------------------------------
FROM node:22-alpine AS frontend-builder

WORKDIR /app

# Cache package dependencies
COPY package.json package-lock.json ./
RUN npm ci --prefer-offline --no-audit

# Copy application sources scanned by Tailwind CSS and bundled by Vite
COPY vite.config.js tailwind.config.js* postcss.config.js* ./
COPY resources/ resources/
COPY app/ app/
COPY routes/ routes/

# Build static assets to public/build
RUN npm run build


# ------------------------------------------------------------------------------
# Stage 2: Install PHP Composer Dependencies
# ------------------------------------------------------------------------------
FROM composer:2 AS composer-builder

WORKDIR /app

# Cache vendor dependencies layer
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-autoloader \
    --no-scripts \
    --ignore-platform-reqs

# Copy application files required to build optimized autoload classmap
COPY app/ app/
COPY bootstrap/ bootstrap/
COPY config/ config/
COPY database/ database/
COPY routes/ routes/

# Generate optimized, authoritative classmap without dev packages
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative


# ------------------------------------------------------------------------------
# Stage 3: Production Runtime (Nginx + PHP-FPM via Supervisord on Alpine Linux)
# ------------------------------------------------------------------------------
FROM php:${PHP_VERSION}-fpm-alpine AS production

LABEL maintainer="ManaCouture Team"
LABEL description="Production container image for ManaCouture Laravel E-Commerce"

# Install system dependencies, Nginx, and Supervisord
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    curl \
    zip \
    unzip \
    bash \
    ca-certificates

# Install official docker-php-extension-installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install mission-critical extensions for Laravel e-commerce:
# - bcmath: Accurate monetary & price math calculations
# - gd: Image resizing, watermarking, invoice PDF generation (DomPDF)
# - zip: Excel exports/imports (Maatwebsite Excel) & invoice archives
# - intl: Currency formatting, NumberFormatter, multi-locale handling
# - opcache: Production opcode pre-compilation
# - redis: Low-latency sessions, caching, queues
# - pcntl & posix: Queue workers and signal management
# - exif: Digital photo orientation and upload metadata
# - pdo_mysql & pdo_pgsql & pdo_sqlite: Database connectivity
RUN install-php-extensions \
    bcmath \
    gd \
    zip \
    intl \
    opcache \
    redis \
    pcntl \
    posix \
    exif \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite

# Configure PHP & OPcache
COPY docker/php.ini /usr/local/etc/php/conf.d/99-production.ini
COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Configure Nginx & Supervisord
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Configure container startup script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set working directory
WORKDIR /var/www/html

# Copy application source code (respecting .dockerignore)
COPY . .

# Copy production vendor directory from composer-builder stage
COPY --from=composer-builder /app/vendor/ /var/www/html/vendor/

# Copy compiled frontend assets from frontend-builder stage
COPY --from=frontend-builder /app/public/build/ /var/www/html/public/build/

# Create required storage directories and set correct ownership to www-data
RUN mkdir -p \
    /var/www/html/storage/app/public \
    /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache \
    /var/log/supervisor \
    /run/nginx \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/log/nginx /var/log/supervisor /run/nginx \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose HTTP port
EXPOSE 80

# Health check using Laravel's native /up endpoint
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://127.0.0.1/up || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
