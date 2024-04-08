<?php


namespace Untek\Framework\Telegram\Domain\Entities;


use Untek\Core\Arr\Helpers\ArrayHelper;

class ResponseEntity
{

    private $chatId;
    private $text;
    private $replyMarkup;
    private $parseMode;
    private $disableWebPagePreview;
    private $disableNotification;

    /**
     * @return mixed
     */
    public function getChatId()
    {
        return $this->chatId;
    }

    /**
     * @param mixed $chatId
     */
    public function setChatId($chatId): void
    {
        $this->chatId = $chatId;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getReplyMarkup()
    {
        return $this->replyMarkup;
    }

    public function setKeyboard(array $keyboard): void
    {
        $default = [
            'one_time_keyboard' => false,
            'resize_keyboard' => true,
            'selective' => true,
        ];
        $keyboard = ArrayHelper::merge($default, $keyboard);
        $this->setReplyMarkup(json_encode($keyboard, true));
    }

    /**
     * @param mixed $replyMarkup
     */
    public function setReplyMarkup($replyMarkup): void
    {
        $this->replyMarkup = $replyMarkup;
    }

    /**
     * @return mixed
     */
    public function getParseMode()
    {
        return $this->parseMode;
    }

    /**
     * @param mixed $parseMode
     */
    public function setParseMode($parseMode): void
    {
        $this->parseMode = $parseMode;
    }

    /**
     * @return mixed
     */
    public function getDisableWebPagePreview()
    {
        return $this->disableWebPagePreview;
    }

    /**
     * @param mixed $disableWebPagePreview
     */
    public function setDisableWebPagePreview($disableWebPagePreview): void
    {
        $this->disableWebPagePreview = $disableWebPagePreview;
    }

    /**
     * @return mixed
     */
    public function getDisableNotification()
    {
        return $this->disableNotification;
    }

    /**
     * @param mixed $disableNotification
     */
    public function setDisableNotification($disableNotification): void
    {
        $this->disableNotification = $disableNotification;
    }

}
