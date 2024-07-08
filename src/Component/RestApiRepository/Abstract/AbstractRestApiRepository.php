<?php

namespace Untek\Component\RestApiRepository\Abstract;

use Untek\Component\RestApiRepository\Helpers\RequestHelper;
use Untek\Database\Base\Hydrator\Traits\NormalizerTrait;

abstract class AbstractRestApiRepository
{

    use NormalizerTrait;

    protected RequestHelper $requestHelper;

    public function __construct(string $baseUrl)
    {
        $this->requestHelper = new RequestHelper($baseUrl);
    }

    protected function sendRequest(string $path, array $queryParams = [], string $method = 'GET', array $headers = []): array
    {
        return $this->requestHelper->sendRequest($path, $queryParams, $method, $headers);
    }
}