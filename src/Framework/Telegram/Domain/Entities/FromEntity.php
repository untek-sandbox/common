<?php


namespace Untek\Framework\Telegram\Domain\Entities;


class FromEntity
{

    private $id;
    private $isBot;
    private $firstName;
    private $username;
    private $languageCode;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getIsBot()
    {
        return $this->isBot;
    }

    public function setIsBot($isBot): void
    {
        $this->isBot = $isBot;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getLanguageCode()
    {
        return $this->languageCode;
    }

    public function setLanguageCode($languageCode): void
    {
        $this->languageCode = $languageCode;
    }

}
