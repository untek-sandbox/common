<?php


namespace Untek\Framework\Telegram\Domain\Entities;


class BotEntity
{

    private $id;
    private $key;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key): void
    {
        $this->key = $key;
    }

    public function getToken(): string {
        return $this->getId() . ':' . $this->getKey();
    }

    public function setToken(string $token) {
        list($this->id, $this->key) = explode(':', $token);
    }
}