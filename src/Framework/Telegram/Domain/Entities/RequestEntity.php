<?php


namespace Untek\Framework\Telegram\Domain\Entities;


class RequestEntity
{

    private $id;
    private $message;
    private $callbackQuery;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getMessage(): ?MessageEntity
    {
        return $this->message;
    }

    public function setMessage(MessageEntity $message): void
    {
        $this->message = $message;
    }

    public function getCallbackQuery()
    {
        return $this->callbackQuery;
    }

    public function setCallbackQuery($callbackQuery): void
    {
        $this->callbackQuery = $callbackQuery;
    }

}

/*{
    "update_id": 692451587,
    "message": {

    }
}*/