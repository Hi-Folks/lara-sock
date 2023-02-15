<?php

namespace HiFolks\LaraSock\Commands;

use HiFolks\LaraSock\Contracts\WebSocketHandlerInterface;
use HiFolks\LaraSock\Dispatcher;
use HiFolks\LaraSock\WebSocketHandler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class LaraSockCommand extends Command
{
    public $signature = 'larasock:start
        {--host=127.0.0.1 : The IP address the server should bind to}
        {--port=9501 : The port the server should be available on}
        {--logchannel= : The log channel to log web socket activities}
        ';

    public $description = 'Start WebSocket Server';

    private WebSocketHandlerInterface $handler;

    protected function getOptionWithFallbacks(string $key, $default = '')
    {
        $domain = 'larasock';
        $key = Str::lower($key);

        return $this->option($key)
            ?? config($domain.'.'.$key)
            ?? $_ENV[Str::upper($domain).'_'.Str::upper($key)]
            ?? $default;
    }

    protected function getHost()
    {
        return $this->getOptionWithFallbacks('host', '0.0.0.0');
    }

    protected function getLogChannel()
    {
        return $this->getOptionWithFallbacks('logchannel', 'null');
    }

    protected function getPort()
    {
        return $this->getOptionWithFallbacks('port', 9501);
    }

    public function handle(): int
    {
        $port = $this->getPort();
        $host = $this->getHost();

        $this->line('<bg=blue> LaraSock '.
            \Composer\InstalledVersions::getPrettyVersion('hi-folks/lara-sock').' </>');
        $this->line('<fg=gray> '.
            $this->getDescription().' </>');
        $dispatcher = new Dispatcher($host, $port);

        //$server = new Server($address, $port);
        $logChannel = Log::channel($this->getLogChannel());
        $handler = new WebSocketHandler();
        $handler->setLogger($logChannel);
        $dispatcher->setWebSocketHandler($handler);
        $logChannel->info('Starting');
        $dispatcher->start();

        /*
        $server->on('start', function (Server $server) {
            $this->components->twoColumnDetail(
                '<fg=white;options=bold>Swoole WebSocket Server</>',
                '<fg=green;options=bold>Started</>'
            );
            $this->components->twoColumnDetail('WebSocket listening to address', $server->host);
            $this->components->twoColumnDetail('WebSocket listening to port', $server->port);
        });

        $server->on('Open', function (Server $server, Request $request) {
            $this->line("New connection {$request->fd}");
            $this->handler->onOpen($server, $request);
        });

        $server->on('Message', function (Server $server, Frame $frame) {
            $this->line("Received message from {$frame->fd}");
        });
        $server->on('Close', function (Server $server, int $fd) {
            $this->line("Connection close by: {$fd}");
        });
        $server->on('Disconnect', function (Server $server, int $fd) {
            $this->line("Disconnection from: {$fd}");
        });

        $server->start();
        */

        return self::SUCCESS;
    }
}
