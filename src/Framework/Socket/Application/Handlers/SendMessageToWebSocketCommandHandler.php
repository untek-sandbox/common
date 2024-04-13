<?php

namespace Untek\Framework\Socket\Application\Handlers;

use Untek\Framework\Socket\Application\Commands\SendMessageToWebSocketCommand;
use Untek\Framework\Socket\Application\Services\MessageTransportInterface;
use Untek\Framework\Socket\Infrastructure\Dto\SocketEvent;

class SendMessageToWebSocketCommandHandler
{

    public function __construct(private MessageTransportInterface $socketDaemon)
    {
    }

    public function __invoke(SendMessageToWebSocketCommand $command)
    {
        $event = new SocketEvent();
        $event->setUserId($command->getToUserId());
        $event->setFromUserId($command->getFromUserId());
        $event->setName($command->getName());
        $event->setPayload($command->getPayload());
        $this->socketDaemon->sendMessageToTcp($event);
    }
}
