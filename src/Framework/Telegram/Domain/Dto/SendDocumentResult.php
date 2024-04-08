<?php

namespace Untek\Framework\Telegram\Domain\Dto;

class SendDocumentResult
{

    private int $messageId;
    private string $caption;
    private int $date;
    private From $from;
    private Chat $chat;
    private Document $document;
    private array $captionEntities;

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
    public function getCaption(): string
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     */
    public function setCaption(string $caption): void
    {
        $this->caption = $caption;
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

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @param Document $document
     */
    public function setDocument(Document $document): void
    {
        $this->document = $document;
    }

    /**
     * @return array
     */
    public function getCaptionEntities(): array
    {
        return $this->captionEntities;
    }

    /**
     * @param array $captionEntities
     */
    public function setCaptionEntities(array $captionEntities): void
    {
        $this->captionEntities = $captionEntities;
    }

}