<?php

namespace HiFolks\LaraSock\Objects;

use Illuminate\Support\Arr;

class Channels
{
    const DEFAULT_CHANNEL_NAME = 'default';

    private array $channels;

    public function __construct()
    {
        $this->channels = [];
        $this->createChannel(self::DEFAULT_CHANNEL_NAME);
    }

    public function createChannel($name)
    {
        if (Arr::exists($this->channels, $name)) {
            // error
            return false;
        }
        $this->channels[$name] = new \OpenSwoole\Table(1024);
        $this->channels[$name]->column('fd', \OpenSwoole\Table::TYPE_INT);
        $this->channels[$name]->column('name', \OpenSwoole\Table::TYPE_STRING, 64);
        $this->channels[$name]->create();

        return true;
    }

    public function getChannel($channelName = self::DEFAULT_CHANNEL_NAME)
    {
        return Arr::get($this->channels, $channelName, false);
    }

    public function totalSubscribers($channelName = self::DEFAULT_CHANNEL_NAME)
    {
        return $this->getChannel($channelName)->count();
    }

    public function addSubscriber(int $fd, $channelName = self::DEFAULT_CHANNEL_NAME)
    {
        $clientName = sprintf("Client-%'.06d\n", $fd);
        $data = [
            'fd' => $fd,
            'name' => sprintf($clientName),
        ];
        $this->channels[$channelName]->set($fd, $data);

        return $data;
    }

    public function removeSubscriber(int $fd, $channelName = self::DEFAULT_CHANNEL_NAME)
    {
        $this->channels[$channelName]->del($fd);
    }

    public function getSubscribers($channelName = self::DEFAULT_CHANNEL_NAME)
    {
        $channel = Arr::get($this->channels, $channelName, false);
        if ($channel === false) {
            return false;
        }
    }
}
