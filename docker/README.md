# Docker

[![dockeri.co](https://dockeri.co/image/2fauth/2fauth)](https://hub.docker.com/r/2fauth/2fauth)

You can run 2fauth in a single Docker container.

## Features

- Runs without root as user `www-data`
- Only **182MB** (uncompressed amd64 image)
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
    -v /yourpath/2fauth:/2fauth qmcgaw/2fauth
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

## Implementation details

- The container is based on `debian:buster-slim`
- The container runs an Nginx server together with PHP-FPM as a  system service.
- The `/srv` directory holds the repository data and PHP code.
- The `/2fauth` directory is targeted for the container end users.
- By default the container logs the Nginx logs and the PHP-FPM logs. The application logs can be found in `/2fauth/storage/logs`.

## TODOs

- Write short commit hash to installed file to only migrate on commit change
- Base image (or other image) on Alpine.
- Setup CI to build image on push to master
- Change Dockerfile and CI to cross build for all architectures.
