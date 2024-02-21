<?php

namespace Untek\Framework\Socket\Application\Services;

use Untek\Framework\Socket\Infrastructure\Dto\SocketEvent;

interface SocketDaemonInterface
{

    public function sendMessageToTcp(SocketEvent $eventEntity);

    public function runAll(bool $daemonize = false);
}
