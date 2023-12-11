<?php

namespace Untek\Framework\Telegram\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use RuntimeException;

class TelegramMessage
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
    public function sendMessageToChat(int $chatId, string $message, string $parseMode = 'Markdown')
    {
        $requestData = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => $parseMode,
        ];
        $requestQuery = http_build_query($requestData);

        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage?{$requestQuery}";
        $client = new Client();
        try {
            $client->request('POST', $url);
        } catch (TransferException | GuzzleException $exception) {
            throw new RuntimeException('Message not sent.');
        }
    }
}
