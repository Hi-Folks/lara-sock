<?php

namespace HiFolks\LaraSock\Contracts;

use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;
use OpenSwoole\WebSocket\Server;

interface WebSocketHandlerInterface
{
    public function setLogger($logger);

    public function onStart(Server $server);

    public function onOpen(Server $server, Request $request);

    public function onMessage(Server $server, Frame $frame);

    public function onClose(Server $server, int $fd);

    public function onDisconnect(Server $server, int $fd);
}
