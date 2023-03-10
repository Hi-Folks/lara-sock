# LaraSock


![CI/CD Github Actions](https://img.shields.io/github/actions/workflow/status/hi-folks/lara-sock/php-code-quality.yml?style=for-the-badge)
![GitHub last commit](https://img.shields.io/github/last-commit/hi-folks/lara-sock?style=for-the-badge)
![GitHub Release Date](https://img.shields.io/github/release-date/hi-folks/lara-sock?style=for-the-badge)
![Packagist PHP Version](https://img.shields.io/packagist/v/hi-folks/lara-sock?style=for-the-badge)

![LaraSock](lara-sock.png)


> This is an early stage (under costruction/exploration) work in progress, so at the moment, the project doens't implement
> all the functionalities that you expect. If [you want to contribute](#contributing), providing Pull Request
>  providing suggestions, feel free to share. We believe in positive vibes.


## Why LaraSock?

Larasock implements a WebSocket Server based on Open Swoole.
The final goal is to support the same Application Providers supported by Laravel Octane (Swoole and Roadrunner).
This package aims to allow you to easily add realtime functionalities via WebSocket to your Octane application.

So, if you are already using Open Swoole with your Laravel Octane,
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
### The client
Once you started the Web Socket Server, you can start creating your Web client to send and receive messages.
To do that you can implement your HTML page and using Websocket Javascript class.

```html

<!doctype html>
<html>

<head>
    <title> WebSocket with PHP and Open Swoole </title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.50.0/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let echo_service;
        append = function(text) {
            document.getElementById("websocket_events").insertAdjacentHTML('afterbegin',
                "<li class='border-solid border-y-2 border-indigo-400'>" + text + ";</li>"
            );
        }
        window.onload = function() {
            echo_service = new WebSocket('ws://127.0.0.1:9501');
            echo_service.onmessage = function(event) {
                console.log(event.data)
                append(event.data)
            }
            echo_service.onopen = function() {
                append("Connected to WebSocket!");
            }
            echo_service.onclose = function() {
                append("Connection closed");
            }
            echo_service.onerror = function() {
                append("Error happens");
            }
        }

        function sendMessage(event) {
            console.log(event)
            let message = document.getElementById("message").value;
            echo_service.send(message);
        }
    </script>
</head>

<body>
    <div class=" px-20 py-20" data-theme="acid">
        Message:
        <div class="form-control">
            <div class="input-group">
                <input id="message" value="Hello!" type="text" placeholder="Search???" class="input input-bordered" />
                <button class="btn btn-square" onclick="sendMessage(event)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>
        <ul class="p-4" id="websocket_events">
        </ul>
    </div>
</body>

</html>
```
## A note about the Subprotocol

WebSocket defines a protocol that allows clients and server to exchange data (messages).
A **Sub**-protocol defines the structure of the exchanged message and the meanings of each field. For example, you want to exchange a pure string with the text message without any additional information. Or you want to exchange data in with a more complex.

At the moment the current implementation of this Proof of Concept exchange messages in string format.


## Next Step, evolution of the Proof of Concept

This is just a Proof of Concept the thing that I would like to focus one (and feel free to share any suggestion/feedback/pullrequest):

- Define the structure of the message
- allow to customize the broadcast method
- Rest API for showing statistics

The package is under construction, so if you have some suggestion you can:
- [Write a Feature request](https://github.com/Hi-Folks/lara-sock/issues/new?labels=feature-request&title=%5BFeature+Request%5D%3A++)
- [Submit a Pull Request](https://github.com/Hi-Folks/lara-sock/pulls)
- [Write me on Twitter](https://twitter.com/RmeetsH)

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Submit ideas or feature requests or issues

The package is under construction, so if you have some suggestion you can:

* Take a look if your request is already there [https://github.com/Hi-Folks/lara-sock/issues](https://github.com/Hi-Folks/lara-sock/issues)
* If it is not present, you can create a new [feature request](https://github.com/Hi-Folks/lara-sock/issues/new?labels=feature-request&title=%5BFeature+Request%5D%3A++)
* [Submit a Pull Request](https://github.com/Hi-Folks/lara-sock/pulls)
* [Write me a message via Twitter](https://twitter.com/RmeetsH)

## Credits

- [Roberto Butti](https://github.com/roberto-butti)
- [All Contributors](https://github.com/Hi-Folks/lara-sock/graphs/contributors)

## Who talks about LaraSock
- [Teasing Tweet](https://twitter.com/RmeetsH/status/1625631431664836608)


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.