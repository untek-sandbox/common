<?php

namespace Untek\Sandbox\Sandbox\EgovData\Domain\Libs;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

class EgovDataClient
{

    private $apiKey;

    public function __construct(string $apiKey = null)
    {
        $this->apiKey = $apiKey;
    }

    public function request(string $uri, array $params = []): array
    {
        $client = $this->getClient();
        $options = $this->createOptions($params);
        $response = $client->get($uri, $options);

        return $this->extractBodyFromResponse($response);
    }

    private function getApiKey(): string {
        if(empty($this->apiKey)) {
            $this->apiKey = getenv('EGOV_API_KEY');
            if(empty($this->apiKey)) {
                throw new \Exception('Empty e.gov token!');
            }
        }
        return $this->apiKey;
    }

    private function createOptions(array $params = []): array
    {
        $options = [];

        $query['apiKey'] = $this->getApiKey();
        $query['source'] = json_encode($params, JSON_UNESCAPED_UNICODE);

        //$query = http_build_query($query);
        //$query = urldecode($query);
        //dd($query);

        $options['query'] = $query;
        return $options;
    }

    private function extractBodyFromResponse(ResponseInterface $response): array
    {
        $response->getBody()->rewind();
        $content = $response->getBody()->getContents();
        return json_decode($content);
    }

    private function getClient(): GuzzleClient
    {
        $client = new GuzzleClient([
            "defaults" => [
//                "verify" => __DIR__ . "/../data/nca_rsa.pem",
                //'verify' => false,
            ],
            'base_uri' => 'http://data.egov.kz',
        ]);
        //$client->setDefaultOption('verify', false);
        return $client;
    }
}

// openssl x509 -inform DER -in nca_rsa.crt -out nca_rsa.pem -text
// curl --remote-name --time-cond cacert.pem https://curl.se/ca/cacert.pem
