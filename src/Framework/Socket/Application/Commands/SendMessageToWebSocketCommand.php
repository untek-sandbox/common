<?php

namespace Untek\Framework\Socket\Application\Commands;

use Untek\Framework\Socket\Infrastructure\Dto\SocketEvent;

class SendMessageToWebSocketCommand
{

    private string $name;
    private $fromUserId = null;
    private $toUserId = null;
    private $payload;

    public function __construct(
        string $name = null,
        $fromUserId = null,
        $toUserId = null,
        $payload = null,
    )
    {
        if($name) {
            $this->setName($name);
        }
        if($fromUserId) {
            $this->setFromUserId($fromUserId);
        }
        if($toUserId) {
            $this->setToUserId($toUserId);
        }
        if($payload) {
            $this->setPayload($payload);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getToUserId()
    {
        return $this->toUserId;
    }

    public function setToUserId($toUserId): void
    {
        $this->toUserId = $toUserId;
    }

    public function getFromUserId()
    {
        return $this->fromUserId;
    }

    public function setFromUserId($fromUserId): void
    {
        $this->fromUserId = $fromUserId;
    }

    public function getPayload(): mixed
    {
        return $this->payload;
    }

    public function setPayload(mixed $payload): void
    {
        $this->payload = $payload;
    }
}
