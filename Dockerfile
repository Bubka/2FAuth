ARG BUILDPLATFORM=linux/amd64
ARG TARGETPLATFORM
ARG ALPINE_VERSION=3.14
ARG PHP_VERSION=7.4-alpine${ALPINE_VERSION}
ARG COMPOSER_VERSION=2.1
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
COPY artisan composer.json ./
COPY database ./database
RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader
RUN composer dump-autoload --no-scripts --no-dev --optimize

FROM --platform=${BUILDPLATFORM} vendor AS test
COPY . .
RUN mv .env.testing .env
RUN composer install
RUN php artisan key:generate
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
    php7 \
    # Composer dependencies
    php7-phar \
    # PHP SQLite driver
    php7-pdo_sqlite php7-sqlite3 \
    # PHP extensions
    php7-xml php7-gd php7-mbstring php7-tokenizer php7-cli php7-fileinfo php7-bcmath php7-ctype php7-dom \
    # Runtime dependencies
    php7-session php7-json php7-openssl \
    # Nginx and PHP FPM to serve over HTTP
    php7-fpm nginx

# PHP FPM configuration
# Change username and ownership in php-fpm pool config
RUN sed -i '/user = nobody/d' /etc/php7/php-fpm.d/www.conf && \
    sed -i '/group = nobody/d' /etc/php7/php-fpm.d/www.conf && \
    sed -i '/listen.owner/d' /etc/php7/php-fpm.d/www.conf && \
    sed -i '/listen.group/d' /etc/php7/php-fpm.d/www.conf
# Pre-create files with the correct permissions
RUN mkdir /run/php && \
    chown ${UID}:${GID} /run/php /var/log/php7 && \
    chmod 700 /run/php /var/log/php7

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
# RUN composer dump-autoload --no-scripts --no-dev --optimize

# Entrypoint
ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]
COPY --chown=${UID}:${GID} docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod 500 /usr/local/bin/entrypoint.sh

ENV \
    # You can change the name of the app
    APP_NAME=2FAuth \
    # You can leave this on "local". If you change it to production most console commands will ask for extra confirmation.
    # Never set it to "testing".
    APP_ENV=local \
    # Set to true if you want to see debug information in error screens.
    APP_DEBUG=false \
    # This should be your email address
    SITE_OWNER=mail@example.com  \
    # The encryption key for  our database and sessions. Keep this very secure.
    # If you generate a new one all existing data must be considered LOST.
    # Change it to a string of exactly 32 chars or use command `php artisan key:generate` to generate it
    APP_KEY=SomeRandomStringOf32CharsExactly \
    # This variable must match your installation's external address but keep in mind that
    # it's only used on the command line as a fallback value.
    APP_URL=http://localhost \
    # Turn this to true if you want your app to react like a demo.
    # The Demo mode reset the app content every hours and set a generic demo user.
    IS_DEMO_APP=false \
    # The log channel defines where your log entries go to.
    # 'daily' is the default logging mode giving you 5 daily rotated log files in /storage/logs/.
    # Several other options exist. You can use 'single' for one big fat error log (not recommended).
    # Also available are 'syslog', 'errorlog' and 'stdout' which will log to the system itself.
    LOG_CHANNEL=daily \
    # Log level. You can set this from least severe to most severe:
    # debug, info, notice, warning, error, critical, alert, emergency
    # If you set it to debug your logs will grow large, and fast. If you set it to emergency probably
    # nothing will get logged, ever.
    APP_LOG_LEVEL=notice \
    # Database config & credentials
    # DB_CONNECTION can only be sqlite
    DB_CONNECTION=sqlite \
    DB_DATABASE="/srv/database/database.sqlite" \
    # If you're looking for performance improvements, you could install memcached.
    CACHE_DRIVER=file \
    SESSION_DRIVER=file \
    # Mail settings
    # Refer your email provider documentation to configure your mail settings
    # Set a value for every available setting to avoid issue
    MAIL_DRIVER=log \
    MAIL_HOST=smtp.mailtrap.io \
    MAIL_PORT=2525 \
    MAIL_FROM=changeme@example.com \
    MAIL_USERNAME=null \
    MAIL_PASSWORD=null \
    MAIL_ENCRYPTION=null \
    MAIL_FROM_NAME=null \
    MAIL_FROM_ADDRESS=null \
    # Authentication settings
    # The default authentication guard
    # Supported:
    #   'web-guard' : The Laravel built-in auth system (default if nulled)
    #   'reverse-proxy-guard' : When 2FAuth is deployed behind a reverse-proxy that handle authentication
    # WARNING
    # When using 'reverse-proxy-guard' 2FAuth only look for the dedicated headers and skip all other built-in
    # authentication checks. That means your proxy is fully responsible of the authentication process, 2FAuth will
    # trust him as long as headers are presents.
    AUTHENTICATION_GUARD=web-guard \
    # Name of the HTTP headers sent by the reverse proxy that identifies the authenticated user at proxy level.
    # Check your proxy documentation to find out how these headers are named (i.e 'REMOTE_USER', 'REMOTE_EMAIL', etc...)
    # (only relevant when AUTHENTICATION_GUARD is set to 'reverse-proxy-guard')
    AUTH_PROXY_HEADER_FOR_USER=null \
    AUTH_PROXY_HEADER_FOR_EMAIL=null \
    # Custom logout URL to open when using an auth proxy.
    PROXY_LOGOUT_URL=null \
    # WebAuthn settings
    # Relying Party name, aka the name of the application. If null, defaults to APP_NAME
    WEBAUTHN_NAME=2FAuth \
    # Relying Party ID. If null, the device will fill it internally.
    # See https://webauthn-doc.spomky-labs.com/pre-requisites/the-relying-party#how-to-determine-the-relying-party-id
    WEBAUTHN_ID=null \
    # Optional image data in BASE64 (128 bytes maximum) or an image url
    # See https://webauthn-doc.spomky-labs.com/pre-requisites/the-relying-party#relying-party-icon
    WEBAUTHN_ICON=null \
    # Use this setting to control how user verification behave during the
    # WebAuthn authentication flow.
    #
    # Most authenticators and smartphones will ask the user to actively verify
    # themselves for log in. For example, through a touch plus pin code,
    # password entry, or biometric recognition (e.g., presenting a fingerprint).
    # The intent is to distinguish one user from any other.
    #
    # Supported:
    #   'required': Will ALWAYS ask for user verification
    #   'preferred' (default) : Will ask for user verification IF POSSIBLE
    #   'discouraged' : Will NOT ask for user verification (for example, to minimize disruption to the user interaction flow)
    WEBAUTHN_USER_VERIFICATION=preferred \
    # Use this setting to declare trusted proxied.
    # Supported:
    #   '*': to trust any proxy
    #   A comma separated IP list: The list of proxies IP to trust
    TRUSTED_PROXIES=null \
    # Leave the following configuration vars as is.
    # Unless you like to tinker and know what you're doing.
    BROADCAST_DRIVER=log \
    QUEUE_DRIVER=sync \
    SESSION_LIFETIME=12 \
    REDIS_HOST=127.0.0.1 \
    REDIS_PASSWORD=null \
    REDIS_PORT=6379 \
    PUSHER_APP_ID= \
    PUSHER_APP_KEY= \
    PUSHER_APP_SECRET= \
    PUSHER_APP_CLUSTER=mt1 \
    MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}" \
    MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}" \
    MIX_ENV=local

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
