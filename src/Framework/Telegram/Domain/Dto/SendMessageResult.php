<?php

namespace Untek\Framework\Telegram\Domain\Dto;

class SendMessageResult
{

    private int $messageId;
    private string $text;
    private int $date;
    private int $forwardDate;
    private From $from;
    private From $forwardFrom;
    private Chat $chat;

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * @param int $messageId
     */
    public function setMessageId(int $messageId): void
    {
        $this->messageId = $messageId;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @param int $date
     */
    public function setDate(int $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getForwardDate(): int
    {
        return $this->forwardDate;
    }

    /**
     * @param int $forwardDate
     */
    public function setForwardDate(int $forwardDate): void
    {
        $this->forwardDate = $forwardDate;
    }

    /**
     * @return From
     */
    public function getFrom(): From
    {
        return $this->from;
    }

    /**
     * @param From $from
     */
    public function setFrom(From $from): void
    {
        $this->from = $from;
    }

    /**
     * @return From
     */
    public function getForwardFrom(): From
    {
        return $this->forwardFrom;
    }

    /**
     * @param From $forwardFrom
     */
    public function setForwardFrom(From $forwardFrom): void
    {
        $this->forwardFrom = $forwardFrom;
    }

    /**
     * @return Chat
     */
    public function getChat(): Chat
    {
        return $this->chat;
    }

    /**
     * @param Chat $chat
     */
    public function setChat(Chat $chat): void
    {
        $this->chat = $chat;
    }

}