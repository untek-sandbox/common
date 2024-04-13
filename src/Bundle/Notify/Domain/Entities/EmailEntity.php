<?php

namespace Untek\Bundle\Notify\Domain\Entities;

class EmailEntity
{

    private $from;
    private $to;
    private $subject;
    private $body;
    private $html;
    private $attachments;
    private $cc;
    private $bcc;
    private $replyTo;

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setFrom(?string $from): void
    {
        $this->from = $from;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function setTo(string $to): void
    {
        $this->to = $to;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    public function getBody(): ?string
    {
        if(empty($this->body)) {
            return strip_tags($this->html);
        }
        return $this->body;
    }

    public function setBody(?string $body): void
    {
        $this->body = $body;
    }

    public function getHtml()
    {
        if(empty($this->html)) {
            return $this->body;
        }
        return $this->html;
    }

    public function setHtml(?string $html): void
    {
        $this->html = $html;
    }

    public function getAttachments()
    {
        return $this->attachments;
    }

    public function setAttachments($attachments): void
    {
        $this->attachments = $attachments;
    }

    public function getCc(): ?string
    {
        return $this->cc;
    }

    public function setCc(?string $cc): void
    {
        $this->cc = $cc;
    }

    public function getBcc(): ?string
    {
        return $this->bcc;
    }

    public function setBcc(?string $bcc): void
    {
        $this->bcc = $bcc;
    }

    public function getReplyTo(): ?string
    {
        return $this->replyTo;
    }

    public function setReplyTo(?string $replyTo): void
    {
        $this->replyTo = $replyTo;
    }

}
