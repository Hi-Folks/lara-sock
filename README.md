
![LaraSock](lara-sock.png)

# LaraSock

> This is an early stage work in progress, so expect that at the moment, the project doens't implement
> all the functionalities that you expect. If you want to contribute, providing Pull Request
>  providing suggestions, feel free to share. We believe in positive vibes.


## Why LaraSock?

Larasock implements a WebSocket Server based on Swoole or Open Swoole.
The final goal is to support the same Application Providers supported by Laravel Octane.
So, in this case you can easily add realtime functionalities via WebSocket to your Octane application.

So, if you already using Swoole or Open Swoole with your Laravel Octane,
you don't need additional services or external tools to enable the Web Socket functionalities.


## Installing LaraSock

The LaraSock `hi-folks/lara-sock` is provided as PHP package that you can install
in your Laravel project.
To install the package you can use `composer require`:
```bash
composer require hi-folks/lara-sock
```

Ehi, **the package is not yet release**, so for now, if you want to try it, before to execute `composer require` you have to clone the repository,
and in your `composer.json` of your project file :
```json lines
    "repositories": [
        {
            "type": "path",
            "url": "../lara-sock"
        }
    ]
```
and then, be sure that your minimum-stability, in the `composer.json` of your Laravel project is set to `dev`:
```json lines
    "minimum-stability": "dev",
    "prefer-stable": true,
```

## Starting the server

Installing the package in your Laravel project, adds a new command in `php artisan`.
The new `larasock:start` artisan command starts a long live running process that starts a Web Socket server, ready to listen and accept your Web Socket connection from clients.

```bash
php artisan larasock:start
```

### Options

With the command you can use some options:

```
--host[=HOST]     The IP address the server should bind to [default: "127.0.0.1"]
--port[=PORT]     The port the server should be available on [default: "9501"]
```
The default host is 127.0.0.1, it means that
can receive connection from localhost clients.
The default port is 9501

If you want to accept connection from all the clients on the network,
you have to "bind" to 0.0.0.0 ip address:

```shell
php artisan larasock:start --host=0.0.0.0
```