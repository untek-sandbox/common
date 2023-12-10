<?php


namespace Untek\Framework\Telegram\Domain\Repositories\Http;

use GuzzleHttp\Client;

class UpdatesRepository
{

    public function findAll(string $token, int $lastId, int $timeout) {
        $queryParams = [];
        if ($lastId) {
            $queryParams['offset'] = $lastId;
        }
        $queryParams['timeout'] = $timeout;
        $data = $this->sendRequest($token, $queryParams);
        $updates = $data['result'];
        return $updates;
    }
    
    private function sendRequest(string $token, array $queryParams) {
        $url = "https://api.telegram.org/bot{$token}/getUpdates?" . http_build_query($queryParams);
        $client = new Client();
        $response = $client->get($url);
        $content = $response->getBody()->getContents();
        $data = json_decode($content, JSON_OBJECT_AS_ARRAY);
        return $data;
    }
}
