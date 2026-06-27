ARG BUILDPLATFORM=linux/amd64
ARG TARGETPLATFORM
ARG ALPINE_VERSION=3.23
ARG PHP_VERSION=8.4-alpine${ALPINE_VERSION}
ARG COMPOSER_VERSION=2.9
ARG SUPERVISORD_VERSION=v0.7.3

ARG UID=1000
ARG GID=1000

FROM --platform=${BUILDPLATFORM} composer:${COMPOSER_VERSION} AS build-composer
FROM composer:${COMPOSER_VERSION} AS composer
FROM qmcgaw/binpot:supervisord-${SUPERVISORD_VERSION} AS supervisord

FROM --platform=${BUILDPLATFORM} php:${PHP_VERSION} AS vendor
ARG UID=1000
ARG GID=1000
COPY --from=build-composer --chown=${UID}:${GID} /usr/bin/composer /usr/bin/composer
RUN apk add --no-cache unzip
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions gd bcmath
WORKDIR /srv
COPY artisan composer.json composer.lock ./
COPY database ./database
RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader
RUN composer dump-autoload --no-scripts --no-dev --optimize

FROM --platform=${BUILDPLATFORM} vendor AS test
COPY . .
RUN mv .env.testing .env
RUN composer install
RUN php artisan key:generate
COPY docker/php-test.ini /usr/local/etc/php/php.ini
ENTRYPOINT [ "/srv/vendor/bin/phpunit" ]

FROM alpine:${ALPINE_VERSION}
ARG UID=1000
ARG GID=1000

# Composer 2
COPY --from=composer --chown=${UID}:${GID} /usr/bin/composer /usr/bin/composer
# Supervisord from https://github.com/ochinchina/supervisord
COPY --from=supervisord --chown=${UID}:${GID} /bin /usr/local/bin/supervisord

# Install PHP and PHP system dependencies
RUN apk add --update --no-cache \
    # PHP
    php84 \
    # Composer dependencies
    php84-phar \
    # PHP SQLite, MySQL/MariaDB & Postgres drivers
    php84-pdo_sqlite php84-sqlite3 php84-pdo_mysql php84-pdo_pgsql php84-pgsql \
    # PHP extensions
    php84-xml php84-gd php84-mbstring php84-tokenizer php84-fileinfo php84-bcmath php84-ctype php84-dom php-redis \
    # Runtime dependencies
    php84-session php84-openssl \
    # Nginx and PHP FPM to serve over HTTP
    php84-fpm nginx

# PHP FPM configuration
# Change username and ownership in php-fpm pool config
RUN sed -i '/user = nobody/d' /etc/php84/php-fpm.d/www.conf && \
    sed -i '/group = nobody/d' /etc/php84/php-fpm.d/www.conf && \
    sed -i '/listen.owner/d' /etc/php84/php-fpm.d/www.conf && \
    sed -i '/listen.group/d' /etc/php84/php-fpm.d/www.conf
# Pre-create files with the correct permissions
RUN mkdir /run/php && \
    chown ${UID}:${GID} /run/php /var/log/php84 && \
    chmod 700 /run/php /var/log/php84

# NGINX
# Clean up
RUN rm /etc/nginx/nginx.conf && \
    chown -R ${UID}:${GID} /var/lib/nginx
# configuration
EXPOSE 8000/tcp
RUN touch /run/nginx/nginx.pid /var/lib/nginx/logs/error.log && \
    chown ${UID}:${GID} /run/nginx/nginx.pid /var/lib/nginx/logs/error.log
COPY --chown=${UID}:${GID} docker/nginx.conf /etc/nginx/nginx.conf
RUN nginx -t

# Supervisord configuration
COPY --chown=${UID}:${GID} docker/supervisord.conf /etc/supervisor/supervisord.conf

# Create end user directory
RUN mkdir -p /2fauth && \
    chown -R ${UID}:${GID} /2fauth && \
    chmod 700 /2fauth

# Create /srv internal directory
WORKDIR /srv
RUN chown -R ${UID}:${GID} /srv && \
    chmod 700 /srv

# Run without root
USER ${UID}:${GID}

# Dependencies
COPY --from=vendor --chown=${UID}:${GID} /srv/vendor /srv/vendor

# Copy the rest of the code
COPY --chown=${UID}:${GID} . .
RUN composer dump-autoload --no-scripts --no-dev --optimize

# Entrypoint
ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]
COPY --chown=${UID}:${GID} docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod 500 /usr/local/bin/entrypoint.sh

ENV \
    LOG_CHANNEL=daily \
    LOG_LEVEL=info \
    DB_CONNECTION=sqlite \
    DB_DATABASE="/srv/database/database.sqlite" \
    WEBAUTHN_NAME=2FAuth

ARG VERSION=unknown
ARG CREATED="an unknown date"
ARG COMMIT=unknown
ENV \
    VERSION=${VERSION} \
    CREATED=${CREATED} \
    COMMIT=${COMMIT}
LABEL \
    org.opencontainers.image.authors="https://github.com/Bubka" \
    org.opencontainers.image.version=$VERSION \
    org.opencontainers.image.created=$CREATED \
    org.opencontainers.image.revision=$COMMIT \
    org.opencontainers.image.url="https://github.com/Bubka/2FAuth" \
    org.opencontainers.image.documentation="https://hub.docker.com/r/2fauth/2fauth" \
    org.opencontainers.image.source="https://github.com/Bubka/2FAuth" \
    org.opencontainers.image.title="2fauth" \
    org.opencontainers.image.description="A web app to manage your Two-Factor Authentication (2FA) accounts and generate their security codes"
