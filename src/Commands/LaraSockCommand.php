<?php

namespace HiFolks\LaraSock\Commands;

use HiFolks\LaraSock\Dispatcher;
use HiFolks\LaraSock\WebSocketHandler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use OpenSwoole\Exception as OsException;

class LaraSockCommand extends Command
{
    public $signature = 'larasock:start
        {--host=127.0.0.1 : The IP address the server should bind to}
        {--port=9501 : The port the server should be available on}
        {--logchannel= : The log channel to log web socket activities}
        ';

    public $description = 'Start WebSocket Server';

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
        try {
            $dispatcher = new Dispatcher($host, $port);
            $dispatcher->setCompression();
            $logChannel = Log::channel($this->getLogChannel());
            $handler = new WebSocketHandler();
            $handler->setLogger($logChannel);
            $dispatcher->setWebSocketHandler($handler);
            $dispatcher->start();
        } catch (OsException $e) {
            $this->error('Error creating Web Socket : ');
            $this->line('<fg=red> -> '.$e->getMessage().'</>');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
