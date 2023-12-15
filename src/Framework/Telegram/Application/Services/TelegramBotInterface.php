<?php

namespace Untek\Framework\Telegram\Application\Services;

interface TelegramBotInterface
{

    public function sendMessage(int $chatId, string $message, string $parseMode = 'Markdown'): array;

    public function sendDocument(int $chatId, string $file, string $caption = null): array;

    public function sendPhoto(int $chatId, string $file, string $caption = null): array;

    public function editMessage(int $chatId, int $messageId, string $message, string $parseMode = 'Markdown'): array;
}
