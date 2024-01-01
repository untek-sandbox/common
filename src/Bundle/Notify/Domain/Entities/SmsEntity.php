<?php

namespace Untek\Bundle\Notify\Domain\Entities;

class SmsEntity
{

    private $phone;
    private $message;

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message): void
    {
        $this->message = $message;
    }

}
