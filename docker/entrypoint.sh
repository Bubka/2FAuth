#!/bin/sh

set -e

echo "Running version ${VERSION} commit ${COMMIT} built on ${CREATED}"

# Show versions
echo "supervisord version: $(supervisord version)"
php-fpm7 -v | head -n 1
nginx -v

if [ "${DB_CONNECTION}" = "sqlite" ]; then
  if [ ! -f /2fauth/database.sqlite ]; then
    touch /2fauth/database.sqlite
  fi
  rm -f /srv/database/database.sqlite
  ln -s /2fauth/database.sqlite /srv/database/database.sqlite
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
    php artisan migrate
    php artisan config:clear
  fi
else
  php artisan migrate:refresh
  php artisan passport:install
fi

echo "${COMMIT}" > /2fauth/installed
php artisan storage:link
php artisan config:cache

supervisord
