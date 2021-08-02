#!/bin/bash

set -e

# Show versions
echo "supervisord version: $(supervisord version)"
php-fpm7.3 -v | head -n 1
nginx -v

if [ "${DB_CONNECTION}" = "sqlite" ]; then
  if [ ! -f /2fauth/database.sqlite ]; then
    touch /2fauth/database.sqlite
  fi
  rm -f /srv/database/database.sqlite
  ln -sF /2fauth/database.sqlite /srv/database/database.sqlite
fi

# Inject storage in /2fauth and use it with a symlink
if [ ! -d /2fauth/storage ]; then
  mv /srv/storage /2fauth/storage
else
  rm -r /srv/storage
fi
ln -sF /2fauth/storage /srv/storage

if [ -f /2fauth/installed ]; then
  php artisan migrate
  php artisan config:clear
  php artisan storage:link
  php artisan config:cache
else
  php artisan migrate:refresh
  php artisan passport:install
  php artisan storage:link
  php artisan config:cache
  echo "do not remove me" > /2fauth/installed
fi

supervisord
