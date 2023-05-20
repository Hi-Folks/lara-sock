<?php

namespace HiFolks\LaraSock;

use HiFolks\LaraSock\Contracts\WebSocketHandlerInterface;
use HiFolks\LaraSock\Objects\Channels;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;
use OpenSwoole\WebSocket\Server;

//use OpenSwoole\WebSocket\Server;

class WebSocketHandler implements WebSocketHandlerInterface
{
    private $logger;

    private readonly Channels $channels;

    public function __construct()
    {
        $this->channels = new Channels();
    }

    public function setLogger($logger): void
    {
        $this->logger = $logger;
    }

    public function onOpen(Server $server, Request $request): void
    {
        $fd = $request->fd;

        $subscriber = $this->channels->addSubscriber($fd);
        $this->logger->info("Connection <{$subscriber['fd']}> open by {$subscriber['name']}. Total connections: ".$this->channels->totalSubscribers());
    }

    public function onStart(Server $server): void
    {
        $this->logger->info(__METHOD__);
        \OpenSwoole\Timer::tick(10_000, function (): void {
            $this->logger->info('Memory usage: '.memory_get_usage());
        });
    }

    public function onMessage(Server $server, Frame $frame): void
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

    private function broadcastMessage($data, Server $server, $fd): void
    {
        $this->channels->getChannel()->get((string) $fd, 'name');
        foreach ($this->channels->getChannel() as $key => $value) {
            if ($key == $fd) {
                $server->push($fd, 'Message sent');
            } else {
                //$server->push($key, "FROM: {$sender} - MESSAGE: ".$data);
                //dd($data);
                $server->push($key, $data);
            }
        }
    }

    public function onClose(Server $server, int $fd): void
    {
        $this->logger->info(__METHOD__);
        $this->channels->removeSubscriber($fd);
    }

    public function onDisconnect(Server $server, int $fd): void
    {
        $this->logger->info(__METHOD__);
        $this->channels->removeSubscriber($fd);
    }

    private function sendPong(Server $server, int $fd): void
    {
        $pongFrame = new Frame;
        $pongFrame->opcode = \WEBSOCKET_OPCODE_PONG;

        // Push response of pong to client using a frame object
        $server->push($fd, $pongFrame);
    }
}
