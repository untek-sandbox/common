<?php

namespace Untek\Framework\Socket\Application\Services;

use Untek\Framework\Socket\Infrastructure\Dto\SocketEvent;

interface MessageTransportInterface
{

    public function sendMessageToTcp(SocketEvent $eventEntity);
}
