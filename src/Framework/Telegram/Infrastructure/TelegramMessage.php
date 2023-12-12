<?php

namespace Untek\Framework\Telegram\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use RuntimeException;
use Untek\Core\FileSystem\Helpers\FilePathHelper;
use Untek\Core\FileSystem\Helpers\MimeTypeHelper;

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
    public function sendMessageToChat(int $chatId, string $message, string $parseMode = 'Markdown'): array
    {
        $this->sendDocument($chatId, '/home/vitaliy/Загрузки/firefox.tmp/635f879ee8ff8.png');
        $requestData = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => $parseMode,
        ];
        return $this->sendRequest('sendMessage', $requestData);
    }

    public function sendDocument(int $chatId, string $file, string $caption = null): array
    {
        $extension = FilePathHelper::fileExt($file);
        $mime = MimeTypeHelper::getMimeTypeByExt($extension);
        $pureName = basename($file);
        $arrayQuery = [
            'chat_id' => $chatId,
            'caption' => $caption,
            'document' => curl_file_create($file, $mime, $pureName)
        ];
        /*$ch = curl_init('https://api.telegram.org/bot'. $this->botToken .'/sendDocument');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayQuery);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);*/

        $options = [
            'multipart' => toMultiPart([
                'chat_id'=> $chatId,
                'photo'=> fopen($file, 'r')
            ])
        ];

        $client->request('POST', 'sendPhoto', $options);


        dd($res);
    }

    function toMultiPart(array $arr) {
        $result = [];
        array_walk($arr, function($value, $key) use(&$result) {
            $result[] = ['name' => $key, 'contents' => $value];
        });
        return $result;
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

    private function sendRequest(string $path, array $requestData, string $method = 'POST'): array {
        $url = $this->generateUrl($path, $requestData);
        $client = new Client();
        try {
            $response = $client->request($method, $url);
            return json_decode($response->getBody()->getContents(), true);
        } catch (TransferException | GuzzleException $exception) {
            throw new RuntimeException('Message not sent.');
        }
    }

    private function generateUrl(string $path, array $requestData): string {
        $requestQuery = http_build_query($requestData);
        $url = "https://api.telegram.org/bot{$this->botToken}/{$path}?{$requestQuery}";
        return $url;
    }
}
