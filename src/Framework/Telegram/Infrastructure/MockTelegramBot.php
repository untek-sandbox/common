<?php

namespace Untek\Framework\Telegram\Infrastructure;

use RuntimeException;
use Untek\Framework\Telegram\Application\Services\TelegramBotInterface;

class MockTelegramBot implements TelegramBotInterface
{

    public function __construct(private $botToken)
    {
    }

    /**
     * @param int $chatId
     * @param string $message
     * @param string $parseMode
     * @throws RuntimeException
     */
    public function sendMessage(int $chatId, string $message, string $parseMode = 'Markdown'): array
    {
        return [
            'message_id' => 123,
        ];
    }

    public function sendDocument(int $chatId, string $file, string $caption = null): array
    {
        return [
            'message_id' => 123,
        ];
    }

    public function sendPhoto(int $chatId, string $file, string $caption = null): array
    {
        return [
            'message_id' => 123,
        ];
    }

    public function editMessage(int $chatId, int $messageId, string $message, string $parseMode = 'Markdown'): array
    {
        return [
            'message_id' => 123,
        ];
    }
}
