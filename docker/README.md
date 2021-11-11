# Docker

[![Build status](https://github.com/Bubka/2fauth/actions/workflows/ci.yml/badge.svg)](https://github.com/Bubka/2fauth/actions/workflows/ci.yml)

[![dockeri.co](https://dockeri.co/image/2fauth/2fauth)](https://hub.docker.com/r/2fauth/2fauth)

You can run 2fauth in a single Docker container.

## Features

- [![Latest size](https://img.shields.io/docker/image-size/2fauth/2fauth/latest?label=Image%20size)](https://hub.docker.com/r/2fauth/2fauth/tags)
- Compatible with: `amd64`, `386`, `arm64`, `arm/v6` and `arm/v7`
- Stores data in an Sqlite database file
- Runs without root as user with id `1000` and group id `1000`

## Setup

We assume your current directory is `/yourpath`.

1. Create a directory on your host:

    ```sh
    mkdir 2fauth
    ```

1. **If your host is not Windows**: since the container runs without root as user `1000:1000`, you need to fix the ownership and permissions of that directory:

    ```sh
    chown 1000:1000 2fauth
    chmod 700 2fauth
    ```

    💁 if you feel like using another ID, you can [build the image with build arguments](#Build-the-image-with-build-arguments).

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
chown 1000:1000 /yourpath/2fauth/database.sqlite
chmod 700 /yourpath/2fauth/database.sqlite
```

The container will automagically pick it up.

## Update

⚠️ At the very least, backup your `database.sqlite` file to avoid bad surprises!

The Docker image `2fauth/2fauth` is built on every commit pushed to the `master` branch.

You can therefore pull the image with `docker pull 2fauth/2fauth` and restart the container to update it.

You can also use tagged images, see [Docker Hub tags](https://hub.docker.com/r/2fauth/2fauth/tags?page=1&ordering=last_updated) which are produced on Github releases.

## Build the image

You can build the image from the `master` branch with `docker` and `git` using:

```sh
docker build -t 2fauth/2fauth https://github.com/Bubka/2FAuth.git
```

### Build the image for a specific release

You can build a [specific release](https://github.com/Bubka/2FAuth/releases) by appending the release tag with `#<release-tag>` to the command. For example:

```sh
docker build -t 2fauth/2fauth https://github.com/Bubka/2FAuth.git#v2.1.0
```

### Build the image for a specific commit

You can build a specific commit (see [master's commits](https://github.com/Bubka/2FAuth/commits/master)) by appending the commit hash with `#<commit-hash>` to the command. For example:

```sh
docker build -t 2fauth/2fauth https://github.com/Bubka/2FAuth.git#fba9e29bd4e3bb697296bb0bde60ae869537528b
```

### Build the image with build arguments

There are the following build arguments you can use to customize the image using `--build-arg key=value`:

| Build argument | Default | Description |
| --- | --- | --- |
| `UID` | 1000 | The UID of the user to run the container as |
| `GID` | 1000 | The GID of the user to run the container as |
| `DEBIAN_VERSION` | `buster-slim` | The Debian version to use |
| `PHP_VERSION` | `7.4-buster` | The PHP version to use to get composer dependencies |
| `COMPOSER_VERSION` | `2.1` | The version of composer to use |
| `SUPERVISORD_VERSION` | `v0.7.3` | The version of supervisord to use |
| `VERSION` | `unknown` | The version of the image |
| `CREATED` | `an unknown date` | The date of the image build time |
| `COMMIT` | `unknown` | The commit hash of the Git commit used |

## Implementation details

- The final Docker image is based on `alpine:3.14` with minimal packages installed
- The container runs [`supervisord`](https://github.com/ochinchina/supervisord) to handle both an Nginx server and a PHP-FPM server together
- The `/srv` directory holds the repository data and PHP code.
- The `/2fauth` directory is targeted for the container end users.
- By default the container logs the Nginx logs and the PHP-FPM logs. The application logs (if any) can be found in `/2fauth/storage/logs`.
