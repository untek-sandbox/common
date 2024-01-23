<?php 

namespace Untek\Framework\Socket\Domain\Interfaces\Services;

use Exception;

interface ClientMessageHandlerInterface
{

    /**
     * @param mixed $data
     * @return int
     *
     * @throws Exception
     */
    public function onMessage(mixed $data): mixed;
}
