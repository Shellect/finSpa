FROM php:8.2-fpm

ARG HOST_UID=1000
ARG HOST_GID=1000
ARG UNAME=www-data
ARG UGROUP=www-data

RUN usermod -u ${HOST_UID} www-data && groupmod -g ${HOST_GID} www-data

WORKDIR /var/www

USER "${USER_ID}:${GROUP_ID}"



CMD ["php-fpm"]