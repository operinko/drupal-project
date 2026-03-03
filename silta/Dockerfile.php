# Dockerfile for the PHP container.
# Uses a multi-stage build: composer stage installs PHP dependencies + runs scaffold
# (which places drupal/core into web/core/ etc.), then copies everything into
# the slim runtime image.
ARG BASE_IMAGE=wunderio/silta-php-fpm:8.3-fpm-v1

# --- Build stage: install Composer dependencies + run scaffold ---
FROM composer:2 AS composer-build

WORKDIR /app
# Copy full source first so drupal-scaffold can write web/core/, web/index.php, etc.
COPY . .
RUN composer install \
    --no-dev \
    --no-interaction \
    --optimize-autoloader \
    --ignore-platform-reqs

# --- Runtime stage ---
FROM $BASE_IMAGE

COPY --chown=www-data:www-data --from=composer-build /app /app

USER www-data
