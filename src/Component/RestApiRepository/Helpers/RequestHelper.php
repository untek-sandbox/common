<?php

namespace Untek\Component\RestApiRepository\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class RequestHelper
{

    protected string $baseUrl;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function sendRequest(string $path, array $queryParams = [], string $method = 'GET', array $headers = []): array
    {
        $client = new Client();
        $uri = $this->baseUrl . '/' . $path;
        if ($queryParams) {
            $requestQuery = http_build_query($queryParams, "", '&');
            $uri .= '?' . $requestQuery;
        }
        $request = new Request($method, $uri, $headers);
        $response = $client->send($request, ['timeout' => 30]);
        return json_decode($response->getBody()->getContents(), true);
    }
}