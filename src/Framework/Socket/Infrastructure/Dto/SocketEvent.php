<?php

namespace Untek\Framework\Socket\Infrastructure\Dto;

use Untek\Framework\Socket\Domain\Enums\SocketEventStatusEnum;

class SocketEvent {

    private $userId;
    private $fromUserId;
    private $name;
    private $status = SocketEventStatusEnum::OK;
    private $payload;

    public function getUserId()//: int
    {
        return $this->userId;
    }

    public function setUserId(/*int*/ $userId): void
    {
        $this->userId = $userId;
    }

    public function getFromUserId()
    {
        return $this->fromUserId;
    }

    public function setFromUserId($fromUserId): void
    {
        $this->fromUserId = $fromUserId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
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
