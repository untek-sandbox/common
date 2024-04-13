<?php 


namespace Untek\Framework\Socket\Infrastructure\Services;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Framework\Socket\Domain\Interfaces\Services\ClientMessageHandlerInterface;

DeprecateHelper::hardThrow();

class ClientMessageHandler implements ClientMessageHandlerInterface
{

    /**
     * @param mixed $data
     * @return int
     */
    public function onMessage(mixed $data) : mixed
    {
        return $data;
    }
}