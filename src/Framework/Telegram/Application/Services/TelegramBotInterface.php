<?php

namespace Untek\Framework\Telegram\Application\Services;

use Untek\Framework\Telegram\Domain\Dto\ForwardMessageResult;
use Untek\Framework\Telegram\Domain\Dto\SendDocumentResult;
use Untek\Framework\Telegram\Domain\Dto\SendMessageResult;
use Untek\Framework\Telegram\Domain\Dto\SendPhotoResult;

interface TelegramBotInterface
{

    public function sendMessage(int $chatId, string $text, string $parseMode = ''): SendMessageResult;

    public function sendDocument(int $chatId, string $file, string $caption = null, string $parseMode = ''): SendDocumentResult;

    public function sendPhoto(int $chatId, string $file, string $caption = null, string $parseMode = ''): SendPhotoResult;

    public function editMessage(int $chatId, int $messageId, string $text, string $parseMode = ''): SendMessageResult;

    public function forwardMessage(int $fromChatId, int $chatId, int $messageId): ForwardMessageResult;
}
