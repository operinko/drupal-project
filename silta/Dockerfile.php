# Dockerfile for the PHP container.
# ARG BASE_IMAGE allows CI to override the base image version via --build-arg.
ARG BASE_IMAGE=wunderio/silta-php-fpm:8.3-fpm-v1
FROM $BASE_IMAGE

COPY --chown=www-data:www-data . /app

USER www-data
