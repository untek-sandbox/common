<?php

namespace Untek\Framework\Socket\Infrastructure\Dto;

use Symfony\Contracts\EventDispatcher\Event;

class NewMessageEvent extends Event
{

    public function __construct(
        private $fromUserId,
        private $toUserId,
        private string $name,
        private $payload,
    )
    {
    }

    public function getFromUserId()//: int
    {
        return $this->fromUserId;
    }

    public function setFromUserId(/*int*/ $fromUserId): void
    {
        $this->fromUserId = $fromUserId;
    }

    public function getToUserId()
    {
        return $this->toUserId;
    }

    public function setToUserId($toUserId): void
    {
        $this->toUserId = $toUserId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function setPayload($payload): void
    {
        $this->payload = $payload;
    }
}
