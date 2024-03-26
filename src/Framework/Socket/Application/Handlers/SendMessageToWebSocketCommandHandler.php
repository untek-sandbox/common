<?php

namespace Untek\Framework\Socket\Application\Handlers;

use Untek\Framework\Socket\Application\Commands\SendMessageToWebSocketCommand;
use Untek\Framework\Socket\Application\Services\SocketDaemonInterface;
use Untek\Framework\Socket\Infrastructure\Dto\SocketEvent;

class SendMessageToWebSocketCommandHandler
{

    public function __construct(private SocketDaemonInterface $socketDaemon)
    {
    }

    public function __invoke(SendMessageToWebSocketCommand $command)
    {
        $event = new SocketEvent();
        $event->setUserId($command->getToUserId());

        $payload = $command->getPayload();
        $payload['fromUserId'] = $command->getFromUserId();

        $event->setName($command->getName());
        $event->setPayload($payload);
        $this->socketDaemon->sendMessageToTcp($event);
    }
}
