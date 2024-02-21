<?php

namespace Untek\Framework\Socket\Infrastructure\Services;

use Untek\Framework\Socket\Application\Services\SocketDaemonInterface;
use Untek\Framework\Socket\Infrastructure\Dto\SocketEvent;

class SocketDaemonTest implements SocketDaemonInterface
{

    public function __construct()
    {
    }

    public function sendMessageToTcp(SocketEvent $eventEntity)
    {
        // todo: write event to file
    }

    public function runAll(bool $daemonize = false)
    {

    }
}
