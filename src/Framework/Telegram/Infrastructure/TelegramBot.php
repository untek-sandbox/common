<?php

namespace Untek\Framework\Telegram\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use RuntimeException;
use Untek\Core\FileSystem\Helpers\FilePathHelper;
use Untek\Core\FileSystem\Helpers\MimeTypeHelper;
use Untek\Framework\Telegram\Application\Services\TelegramBotInterface;

class TelegramBot implements TelegramBotInterface
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
        $requestData = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => $parseMode,
        ];
        return $this->sendRequest('sendMessage', $requestData);
    }

    public function sendDocument(int $chatId, string $file, string $caption = null): array
    {
        $options = [
            'multipart' => $this->toMultiPart([
                'chat_id'=> $chatId,
                'caption' => $caption,
                'document'=> fopen($file, 'r')
            ])
        ];
        return $this->sendRequest('sendDocument', [], $options);
    }

    public function sendPhoto(int $chatId, string $file, string $caption = null): array
    {
        $options = [
            'multipart' => $this->toMultiPart([
                'chat_id'=> $chatId,
                'caption' => $caption,
                'photo'=> fopen($file, 'r')
            ])
        ];
        return $this->sendRequest('sendPhoto', [], $options);
    }

    public function editMessage(int $chatId, int $messageId, string $message, string $parseMode = 'Markdown'): array
    {
        $requestData = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $message,
            'parse_mode' => $parseMode,
        ];
        return $this->sendRequest('editMessageText', $requestData);
    }

    private function sendRequest(string $path, array $requestData, array $options = [], string $method = 'POST'): array {
        $url = $this->generateUrl($path, $requestData);
        $client = new Client();
        try {
            $response = $client->request($method, $url, $options);
            $result = json_decode($response->getBody()->getContents(), true);
            return $result['result'];
        } catch (TransferException | GuzzleException $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }

    private function generateUrl(string $path, array $requestData): string {
        $requestQuery = http_build_query($requestData);
        $url = "https://api.telegram.org/bot{$this->botToken}/{$path}?{$requestQuery}";
        return $url;
    }

    private function toMultiPart(array $arr): array {
        $result = [];
        array_walk($arr, function($value, $key) use(&$result) {
            $result[] = ['name' => $key, 'contents' => $value];
        });
        return $result;
    }
}
