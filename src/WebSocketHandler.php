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

    private readonly Channels $channels;

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
        $opcode = $frame->opcode;
        switch ($opcode) {
            case 0x10:
                $this->logger->info("PONG frame , opcode ${opcode}, ");
                break;
            case 0x08: // WEBSOCKET_OPCODE_CLOSE
                $this->logger->info("CLOSE frame , opcode ${opcode}, ");
                break;
            case 0x09: // WEBSOCKET_OPCODE_PING
                $this->logger->info("PING frame , opcode ${opcode}, ");
                $this->sendPong($server, $frame->fd);
                break;
            default:
                $this->logger->info("MSG frame , opcode ${opcode}, ");
                $this->broadcastMessage(
                    $frame->data,
                    $server,
                    $frame->fd
                );
        }
    }

    private function broadcastMessage($data, $server, $fd)
    {
        $sender = $this->channels->getChannel()->get(strval($fd), 'name');
        foreach ($this->channels->getChannel() as $key => $value) {
            if ($key == $fd) {
                $server->push($fd, 'Message sent');
            } else {
                $server->push($key, "FROM: {$sender} - MESSAGE: ".$data);
            }
        }
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

    private function sendPong(Server $server, int $fd)
    {
        $pongFrame = new OpenSwoole\WebSocket\Frame;
        $pongFrame->opcode = \WEBSOCKET_OPCODE_PONG;

        // Push response of pong to client using a frame object
        $server->push($fd, $pongFrame);
    }
}
