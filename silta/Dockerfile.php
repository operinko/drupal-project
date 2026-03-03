# Dockerfile for the PHP container.
# Uses a multi-stage build: composer stage installs PHP dependencies,
# then copies vendor/ and app code into the slim runtime image.
ARG BASE_IMAGE=wunderio/silta-php-fpm:8.3-fpm-v1

# --- Build stage: install Composer dependencies ---
FROM composer:2 AS composer-build

WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-interaction --no-scripts --optimize-autoloader --ignore-platform-reqs

# --- Runtime stage ---
FROM $BASE_IMAGE

COPY --chown=www-data:www-data . /app
COPY --chown=www-data:www-data --from=composer-build /app/vendor /app/vendor

USER www-data
