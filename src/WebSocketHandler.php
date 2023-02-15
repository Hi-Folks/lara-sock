<?php

namespace HiFolks\LaraSock;

use HiFolks\LaraSock\Contracts\WebSocketHandlerInterface;
use HiFolks\LaraSock\Objects\Channels;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class WebSocketHandler implements WebSocketHandlerInterface
{
    private $logger;

    private Channels $channels;

    public function __construct()
    {
        $this->channels = new Channels();
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function onOpen(Server $server, Request $request)
    {
        $fd = $request->fd;

        $subscriber = $this->channels->addSubscriber($fd);
        $this->logger->info("Connection <{$subscriber['fd']}> open by {$subscriber['name']}. Total connections: ".$this->channels->totalSubscribers());
    }

    public function onStart(Server $server)
    {
        $this->logger->info(__METHOD__);
    }

    public function onMessage(Server $server, Frame $frame)
    {
        $this->logger->info(__METHOD__);
    }

    public function onClose(Server $server, int $fd)
    {
        $this->logger->info(__METHOD__);
        $this->channels->removeSubscriber($fd);
    }

    public function onDisconnect(Server $server, int $fd)
    {
        $this->logger->info(__METHOD__);
        $this->channels->removeSubscriber($fd);
    }
}
