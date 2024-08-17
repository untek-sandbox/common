<?php

namespace Untek\Component\LogReader\Domain\Model;

use DateTime;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class LogItem
{

    private $id;
    private $message;
    private $context;
    private $level;
    private $level_name;
    private $channel;
    private $extra;
    private $createdAt;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setContext($context): void
    {
        $this->context = $context;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    public function getLevelName(): ?string
    {
        return $this->level_name;
    }

    public function setLevelName(string $level_name): void
    {
        $this->level_name = $level_name;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getExtra()
    {
        return $this->extra;
    }

    public function setExtra($extra): void
    {
        $this->extra = $extra;
    }
}