# Debian Setup

This applies to Debian Buster, but similar instructions should apply for other Debian versions.

## What we will do

- We will use PHP 8.0
- We will use version v3.3.0 of 2fauth
- We will setup to use an Sqlite database
- We will use Nginx and PHP-FPM to serve our site on port `8000`
- We will run all this as user `www-data` without root

## Install dependencies

1. Update your apt repository list:

    ```bash
    apt-get update
    ```

1. Install the following packages:

    ```bash
    apt-get install -y --no-install-recommends \
    php8.0 \
    php8.0-sqlite3 php8.0-mysql \
    php-xml php8.0-gd php8.0-mbstring \
    unzip wget ca-certificates \
    php8.0-fpm nginx
    ```

## Download the code

Let's place 2fauth's code in `/srv`:

```bash
mkdir -p /srv
VERSION=v3.0.0
wget -qO- "https://github.com/Bubka/2FAuth/archive/refs/tags/${VERSION}.tar.gz" | \
    tar -xz --strip-components=1 -C /srv
```

## Nginx configuration

Set your Nginx configuration in `/etc/nginx/nginx.conf` as:

```nginx
events {}
http {
  include mime.types;

  access_log /dev/stdout;
  error_log /dev/stderr;

  server {
      listen 8000;
      server_name 2fAuth;
      root /srv/public;

      index index.php;

      charset utf-8;

      location / {
          try_files $uri $uri/ /index.php?$query_string;
      }

      location = /favicon.ico { access_log off; log_not_found off; }
      location = /robots.txt  { access_log off; log_not_found off; }

      error_page 404 /index.php;

      location ~ \.php$ {
          fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
          fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
          include fastcgi_params;
      }

      location ~ /\.(?!well-known).* {
          deny all;
      }
  }
}
```

You can verify the Nginx configuration is valid with:

```bash
nginx -t
```

## Install composer

Download the latest stable composer:

```bash
wget -qO /usr/local/bin/composer https://getcomposer.org/download/latest-stable/composer.phar
chmod 500 /usr/local/bin/composer
```

## Install supervisord

[Supervisord](https://github.com/ochinchina/supervisord) will be used to manage both Nginx and PHP-FPM.

1. Install it with:

    ```bash
    VERSION=0.7.3
    wget -qO- "https://github.com/ochinchina/supervisord/releases/download/v${VERSION}/supervisord_${VERSION}_Linux_64-bit.tar.gz" | \
        tar -xz --strip-components=1 -C /tmp/ "supervisord_${VERSION}_Linux_64-bit/supervisord_static"
    chmod 500 /tmp/supervisord_static
    mv /tmp/supervisord_static /usr/local/bin/supervisord
    ```

1. Set its configuration in `/etc/supervisor/supervisord.conf` as:

    ```ini
    [supervisord]
    nodaemon=true
    pidfile=/run/supervisord.pid
    loglevel=info

    [program-default]
    stdout_logfile=/dev/stdout
    stdout_logfile_maxbytes=0
    stderr_logfile=/dev/stderr
    stderr_logfile_maxbytes=0
    autorestart=false
    startretries=0

    [program:php-fpm]
    command=/usr/sbin/php-fpm8.0 -F

    [program:nginx]
    command=/usr/sbin/nginx -g 'daemon off;'
    depends_on=php-fpm
    ```

## Fix ownership and permissions for `www-data`

1. Let's fix the ownership and permissions for existing files:

    ```bash
    chown -R www-data \
      /var/lib/nginx/ \
      /var/log/nginx \
      /srv \
      /usr/local/bin/composer \
      /usr/local/bin/supervisord \
      /etc/supervisor/supervisord.conf
    chmod 700 /srv
    ```

1. Let's pre-create some directories and files with the right ownership and permissions:

    ```bash
    mkdir -p /run/php /www/data/.composer
    touch /run/nginx.pid /var/log/php8.0-fpm.log
    chown -R www-data \
      /var/log/php8.0-fpm.log \
      /run/nginx.pid \
      /run/php \
      /www/data/.composer
    chmod 700 /run/php /www/data/.composer
    chmod 600 /var/log/php8.0-fpm.log
    ```

## Change user

Let's run the final commands as `www-data`:

```bash
su -l www-data -s /bin/bash
```

## Install composer dependencies

```bash
cd /srv
composer install --prefer-dist --no-scripts --no-dev --no-autoloader
composer dump-autoload --no-scripts --no-dev --optimize
```

## Create an SQlite database

```bash
touch /srv/database/database.sqlite
chmod 700 /srv/database/database.sqlite
```

## Customize .env file

Use the `/srv/.env.example` file as a template and rename it to `.env`.

```bash
mv /srv/.env.example /srv/.env
```

Make sure you modify:

- `DB_DATABASE` to be `/srv/database/database.sqlite`

## Run 2fauth installation steps

```bash
php artisan migrate:refresh
php artisan passport:install
php artisan storage:link
php artisan config:cache
```

## Run supervisord

```bash
supervisord
```

Now you can access your site at `http://localhost:8000`

You can also run `supervisord -d` to run it as a daemon.

## Upgrade

1. Stop `supervisord`
1. Update the source code in `/srv`. ⚠️ do not change the `/srv/storage` directory nor your `/srv/database/database.sqlite` file.
1. Run the following commands:

    ```bash
    php artisan migrate
    php artisan config:clear
    ```

1. Run `supervisord` again
