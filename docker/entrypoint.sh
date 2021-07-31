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
