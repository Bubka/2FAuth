#!/bin/bash

# TODO fail on any error

if [ "${DB_CONNECTION}" = "sqlite" ]; then
  if [ ! -f /2fauth/.sqlite ]; then
    touch /2fauth/.sqlite
  fi
  rm -f /srv/database/.sqlite
  ln -sF /2fauth/.sqlite /srv/database/.sqlite
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
  echo "" > /2fauth/installed
fi

nginx
sudo service php7.3-fpm stop
