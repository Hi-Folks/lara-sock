<?php

namespace HiFolks\LaraSock;

use HiFolks\LaraSock\Contracts\WebSocketHandlerInterface;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class Dispatcher
{
    private readonly Server $server;

    private array $serverConfiguration = [];

    private WebSocketHandlerInterface $handler;

    public function __construct($host, $port)
    {
        $this->server = new Server($host, $port, \SWOOLE_PROCESS, \SWOOLE_SOCK_TCP /*| \SWOOLE_SSL */);
    }

    public function setWebSocketHandler(WebSocketHandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    private function registerEvents()
    {
        $this->server->on('Start', function (Server $server) {
            $this->handler->onStart($server);
        });
        $this->server->on('message', function (Server $server, Frame $frame) {
            $this->handler->onMessage($server, $frame);
        });
        $this->server->on('open', function (Server $server, Request $request) {
            $this->handler->onOpen($server, $request);
        });
        $this->server->on('close', function (Server $server, int $fd) {
            $this->handler->onClose($server, $fd);
        });
        $this->server->on('disconnect', function (Server $server, int $fd) {
            $this->handler->onDisconnect($server, $fd);
        });
    }

    public function setPingFrame()
    {
    }

    public function start()
    {
        $this->registerEvents();
        $this->server->start();
    }
}
