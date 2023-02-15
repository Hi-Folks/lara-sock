
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

```bash
composer require hi-folks/lara-sock
```

Ehi, the package is not yet release, so if you want to try it, you have to fork it,
and in your `composer.json` file :
```json
    "repositories": [
        {
            "type": "path",
            "url": "../lara-sock"
        }
    ]

```
## Starting the server

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