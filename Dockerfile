ARG DEBIAN_VERSION=buster-slim
ARG COMPOSER_VERSION=2.1

FROM composer:${COMPOSER_VERSION} AS composer

FROM debian:${DEBIAN_VERSION}
ENV DEBIAN_FRONTEND=noninteractive

# Install PHP and PHP system dependencies
RUN apt-get update && \
    apt-get install -y \
    php7.3 \
    php7.3-sqlite3 php7.3-mysql \
    php-xml && \
    apt-get clean && \
    rm -rf /var/cache/* /var/lib/apt/lists/*

# Composer
RUN apt-get update && \
    apt-get install -y unzip composer && \
    apt-get clean && \
    rm -rf /var/cache/* /var/lib/apt/lists/* /usr/bin/composer
# Use composer 2 instead of composer 1
COPY --from=composer --chown=www-data /usr/bin/composer /usr/bin/composer

# PHP FPM
RUN apt-get update && \
    apt-get install -y php7.3-fpm && \
    apt-get clean && \
    rm -rf /var/cache/* /var/lib/apt/lists/*
# Sudo to start PHP-FPM without root
RUN apt-get update && \
    apt-get install -y sudo && \
    apt-get clean && \
    rm -rf /var/cache/* /var/lib/apt/lists/*
RUN echo "www-data ALL = NOPASSWD: /usr/sbin/service php7.3-fpm start, /usr/sbin/service php7.3-fpm status, /usr/sbin/service php7.3-fpm stop" > /etc/sudoers.d/www-data && \
    chmod 0440 /etc/sudoers.d/www-data
# Pre-create directories with the correct permissions
RUN mkdir /run/php && \
    chown www-data /run/php && \
    chmod 700 /run/php

# NGINX
EXPOSE 8000/tcp
RUN apt-get update && \
    apt-get install -y nginx && \
    apt-get clean && \
    rm -rf /var/cache/* /var/lib/apt/lists/* \
    /etc/nginx/nginx.conf && \
    chown -R www-data /var/log/nginx /var/lib/nginx/
RUN touch /run/nginx.pid && \
    chown -R www-data /run/nginx.pid
RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log
COPY --chown=www-data docker/nginx.conf /etc/nginx/nginx.conf

# Create end user directory
RUN mkdir -p /2fauth && \
    chown -R www-data /2fauth && \
    chmod 700 /2fauth

# Create /srv internal directory
WORKDIR /srv
RUN chown -R www-data /srv && \
    chmod 700 /srv

# Fix ownership for /var/www
RUN chown -R www-data /var/www && \
    chmod 700 /var/www

# Run without root
USER www-data

# Dependencies
COPY --chown=www-data artisan composer.json composer.lock ./
# Disable xdebug
RUN phpdismod xdebug
COPY --chown=www-data database ./database
RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader && \
    rm -rf /var/www/.composer

# Copy the rest of the code
COPY --chown=www-data . .
RUN composer dump-autoload --no-scripts --no-dev --optimize

# Nginx
EXPOSE 8000/tcp
COPY --chown=www-data docker/nginx.conf /etc/nginx/nginx.conf
RUN ln -sf /dev/stdout /var/log/nginx/access.log && \
    ln -sf /dev/stderr /var/log/nginx/error.log

# Entrypoint
# ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]
ENTRYPOINT [ "/bin/bash" ]
COPY --chown=www-data docker/entrypoint.sh /usr/local/bin/entrypoint.sh

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
  # DB_CONNECTION can be mysql
  DB_CONNECTION=sqlite \
  DB_DATABASE="/srv/database/.sqlite" \
  # if you want to use MySQL:
  DB_HOST=127.0.0.1 \
  DB_PORT=3306 \
  DB_USERNAME=homestead \
  DB_PASSWORD=secret \
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


