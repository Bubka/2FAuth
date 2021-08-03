# Docker

[![Build status](https://github.com/Bubka/2fauth/actions/workflows/ci.yml/badge.svg)](https://github.com/Bubka/2fauth/actions/workflows/ci.yml)

[![dockeri.co](https://dockeri.co/image/2fauth/2fauth)](https://hub.docker.com/r/2fauth/2fauth)

You can run 2fauth in a single Docker container.

## Features

- Runs without root as user `www-data`
- [![Latest size](https://img.shields.io/docker/image-size/2fauth/2fauth/latest?label=Image%20size)](https://hub.docker.com/r/2fauth/2fauth/tags)
- Compatible with `amd64` only for now

## Setup

1. Create a directory on your host `2fauth`:

    ```sh
    mkdir 2fauth
    ```

1. **If your host is not Windows**: since the container runs without root as user `www-data` (`uid=33(www-data) gid=33(www-data) groups=33(www-data)`), you need to fix the ownership and permissions of that directory:

    ```sh
    chown 33:33 2fauth
    chmod 700 2fauth
    ```

1. Run the container interactively:

    ```sh
    docker run -it --rm -p 8000:8000/tcp \
    -v /yourpath/2fauth:/2fauth 2fauth/2fauth
    ```

1. Access it at [http://localhost:8000](http://localhost:8000)

You can stop it with `CTRL+C`.

- You can also run it in the background by replacing `-it --rm` with `-d`.
- You can set environment variables available (see the [.env.example](.env.example)) with `-e`, for example `-e APP_NAME=2FAuth`.
- You can also use the [docker-compose.yml](docker-compose.yml) with `docker-compose` and modify it as you wish.

### Use an existing SQLite file

If you already have an SQLite file, move it to `/yourpath/2fauth/database.sqlite` on your host before starting the container. Don't forget to fix its ownership and permissions if you run on *nix:

```sh
chown 33:33 /yourpath/2fauth/database.sqlite
chmod 700 /yourpath/2fauth/database.sqlite
```

The container will automagically pick it up.

## Update

The Docker image `2fauth/2fauth` is built on every commit pushed to the `master` branch.

You can therefore pull the image with `docker pull 2fauth/2fauth` and restart the container to update it.

## Build the image

You can build the image from the `master` branch with `docker` and `git` using:

```sh
docker build -t 2fauth/2fauth https://github.com/Bubka/2FAuth.git
```

You can also build a specific commit (see [master's commits](https://github.com/Bubka/2FAuth/commits/master)) by appending the commit hash with `#<commit-hash>` to the command. For example:

```sh
docker build -t 2fauth/2fauth https://github.com/Bubka/2FAuth.git#fba9e29bd4e3bb697296bb0bde60ae869537528b
```

## Change database

If you want to change database, for example switch from SQLite to MySQL, there is no migration yet.

You might want to remove the `installed` file bind mounted in `/2fauth` so the database is re-created.

## Implementation details

- The container is based on `debian:buster-slim`
- The container runs an Nginx server together with PHP-FPM as a  system service.
- The `/srv` directory holds the repository data and PHP code.
- The `/2fauth` directory is targeted for the container end users.
- By default the container logs the Nginx logs and the PHP-FPM logs. The application logs can be found in `/2fauth/storage/logs`.

## TODOs

- Base image (or other image) on Alpine (for a possibly smaller image)
