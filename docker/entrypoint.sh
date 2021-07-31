#!/bin/bash

cleanup() {
  set +e
  echo "Stopping php7.3-fpm service..."
  sudo service php7.3-fpm stop
}
trap cleanup 0
set -e

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

sudo service php7.3-fpm start
sudo service php7.3-fpm status

if [ -f /2fauth/installed ]; then
  php artisan migrate
  php artisan config:clear
else
  php artisan migrate:refresh
  php artisan passport:install
  php artisan storage:link
  php artisan config:cache
  echo "do not remove me" > /2fauth/installed
fi

echo "Nginx listening on :8000"
nginx
