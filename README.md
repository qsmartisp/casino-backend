# Casino-backend

## Prepare

### Get Personal Access Token
> We are use Laravel Nova package.
>
> It's sources are located as separate repo in our gitlab.
>
> So you should generate Personal Access Token (aka PAT).
>
> You can issue your PAT by going to
>
> Profile Icon > Settings > [Access Token](https://gitlab.com/-/profile/personal_access_tokens)
>
> Then to issue a new PAT, write a name and select a scope api/read_api, read_api is a minimum.
> api will work too.
> If you want to set an expiry, can put value in that.
>
> Then press the Create personal access token Button.
>
> Then you’ll be shown your PAT and you need to copy that token.
> It’s only shown once. If you close or refresh the tab, it’ll be gone.

### Set Personal Access Token

> Set PAT in .env
>
> 00000-000000-0000000000000 - PAT which you get from Gitlab

```dotenv
GITLAB_PAT=00000-000000-0000000000000
```

## Initialization

```shell
$ cp .env.example .env
```

```shell
$ composer install
```

## Docker

We are using [Laravel Sail](https://laravel.com/docs/8.x/sail) for local development.

### Start local docker environment

```shell
$ ./vendor/bin/sail up
```

To start all the Docker containers in the background, you may start Sail in "detached" mode:

```shell
$ ./vendor/bin/sail up -d
```

> Once the application's containers have been started, you may access the project in your web browser at: http://localhost.

> To stop all the containers, you may simply press Control + C
> to stop the container's execution.
> Or, if the containers are running in the background, you may use the stop command:
>
> ```shell
> $ ./vendor/bin/sail stop
> ```

### Local overriding of docker-compose file

```shell
$ cp docker-compose.yml docker-compose.override.yml
```

### Manual updating docker-compose.yml

> After updating your application's docker-compose.yml file,
> you should rebuild your container images:
>
> ```shell
> $ ./vendor/bin/sail build --no-cache
>
> $ ./vendor/bin/sail up
> ```

### Laravel app container

Link to sail/app Dockerfile: https://github.com/laravel/sail/blob/v1.13.1/runtimes/8.1/Dockerfile 

### Tinker

```shell
$ ./vendor/bin/sail tinker
```

### Htop of docker container

```shell
$ docker run -it --rm --pid=container:casino-backend-laravel.test-1 terencewestphal/htop
```

### Supervisor web interface

- http://127.0.0.1:9123

> You can change `casino-backend-laravel.test-1` for any another name of container

## Code style

- PSR-12
- For PhpStorm use `./code-style.xml`

## XDebug

You can simply use xdebug3 when you can't understand how your code works even with dump() and dd()!
All what you should do to activate xdebug:

Uncomment this two lines:
```dotenv
#SAIL_PHP_IDE_CONFIG=serverName=casino.backend.local
#SAIL_XDEBUG_MODE=develop,debug
```
Then restart container with sail (aka Docker)
```shell
$ ./vendor/bin/sail up -d --scale laravel.test=0
$ ./vendor/bin/sail up -d --scale laravel.test=1
```
Also check instruction for your IDE; You should activate listening for debug connections
# www
