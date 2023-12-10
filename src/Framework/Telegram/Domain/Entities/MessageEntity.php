<?php


namespace Untek\Framework\Telegram\Domain\Entities;


class MessageEntity
{

    private $id;
    private $from;
    private $chat;
    private $date;
    private $text;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getFrom(): ?FromEntity
    {
        return $this->from;
    }

    public function setFrom(FromEntity $from): void
    {
        $this->from = $from;
    }

    public function getChat(): ?ChatEntity
    {
        return $this->chat;
    }

    public function setChat(ChatEntity $chat): void
    {
        $this->chat = $chat;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text): void
    {
        $this->text = $text;
    }

}

/*

"message_id": 3739,
"from": {
    "id": 123,
    "is_bot": false,
    "first_name": "user123",
    "username": "user123",
    "language_code": "ru"
},
"chat": {
    "id": 123,
    "first_name": "user123",
    "username": "user123",
    "type": "private"
},
"date": 1592736037,
"text": "cxvxc"

*/