#!/bin/sh

set -e

echo "Running version ${VERSION} commit ${COMMIT} built on ${CREATED}"

# Show versions
echo "supervisord version: $(supervisord version)"
php-fpm82 -v | head -n 1
nginx -v

# Database creation
if [ "${DB_CONNECTION}" = "sqlite" ]; then
  # DB_DATABASE is trimmed if necessary
  if [[ $DB_DATABASE == \"* ]] && [[ $DB_DATABASE == *\" ]] ; then
    dbpath=${DB_DATABASE:1:${#DB_DATABASE}-2}
  else
    dbpath=${DB_DATABASE}
  fi
  if [ $dbpath != "/srv/database/database.sqlite" ]; then
    echo "DB_DATABASE sets with custom path: ${dbpath}"
    if [ ! -f ${dbpath} ]; then
      echo "${dbpath} does not exist, we create it"
      touch ${dbpath}
    fi
  else
    echo "DB_DATABASE sets with default path, we will use a symlink"
    echo "Actual db file will be /2fauth/database.sqlite"
    if [ ! -f /2fauth/database.sqlite ]; then
      echo "/2fauth/database.sqlite does not exist, we create it"
      touch /2fauth/database.sqlite
    fi
    rm -f /srv/database/database.sqlite
    ln -s /2fauth/database.sqlite /srv/database/database.sqlite
    echo "/srv/database/database.sqlite is now a symlink to /2fauth/database.sqlite"
  fi
fi

# Inject storage in /2fauth and use it with a symlink
if [ ! -d /2fauth/storage ]; then
  mv /srv/storage /2fauth/storage
else
  rm -r /srv/storage
fi
ln -s /2fauth/storage /srv/storage

# Note: ${COMMIT} is set by the CI
if [ -f /2fauth/installed ]; then
  INSTALLED_COMMIT="$(cat /2fauth/installed)"
  if [ "${INSTALLED_COMMIT}" != "${COMMIT}" ]; then
    echo "Installed commit ${INSTALLED_COMMIT} is different from program commit ${COMMIT}, we are migrating..."
    php artisan cache:clear
    php artisan config:clear
    php artisan migrate --force
  fi
else
  php artisan migrate:refresh --force
  php artisan passport:install --no-interaction
fi

echo "${COMMIT}" > /2fauth/installed
php artisan storage:link --quiet
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

supervisord
