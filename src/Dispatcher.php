<?php

namespace HiFolks\LaraSock;

use HiFolks\LaraSock\Contracts\WebSocketHandlerInterface;
use OpenSwoole\Constant;
use OpenSwoole\Http\Request;
use OpenSwoole\Server as ServerAlias;
use OpenSwoole\WebSocket\Frame;
use OpenSwoole\WebSocket\Server;

class Dispatcher
{
    private readonly Server $server;

    private array $serverConfiguration = [];

    private WebSocketHandlerInterface $handler;

    public function __construct($host, $port)
    {
        $this->server = new Server($host, $port, ServerAlias::SIMPLE_MODE, Constant::SOCK_TCP);
    }

    public function setWebSocketHandler(WebSocketHandlerInterface $handler): void
    {
        $this->handler = $handler;
    }

    private function registerEvents(): void
    {
        $this->server->on('Start', function (Server $server): void {
            $this->handler->onStart($server);
        });
        $this->server->on('message', function (Server $server, Frame $frame): void {
            $this->handler->onMessage($server, $frame);
        });
        $this->server->on('open', function (Server $server, Request $request): void {
            $this->handler->onOpen($server, $request);
        });
        $this->server->on('close', function (Server $server, int $fd): void {
            $this->handler->onClose($server, $fd);
        });
        $this->server->on('disconnect', function (Server $server, int $fd): void {
            $this->handler->onDisconnect($server, $fd);
        });
    }

    public function setPingFrame(): void
    {
    }

    public function serverConfiguration(): void
    {
        if ($this->serverConfiguration !== []) {
            $this->server->set($this->serverConfiguration);
        }
    }

    public function setCompression(): void
    {
        $this->serverConfiguration['websocket_compression'] = true;
    }

    public function start(): void
    {
        $this->serverConfiguration();
        $this->registerEvents();
        $this->server->start();
    }
}
