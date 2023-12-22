<?php

namespace Untek\Framework\Socket\Domain\Libs\Handlers;

use Workerman\Connection\ConnectionInterface;
use Workerman\Worker;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Framework\Socket\Domain\Entities\SocketEventEntity;
use Untek\Framework\Socket\Domain\Libs\Transport;
use Untek\Framework\Socket\Domain\Repositories\Ram\ConnectionRepository;

class TcpHandler
{

    private $transport;
    private $tcpWorker;
    private $wsWorker;
    private $connectionRepository;

    public function __construct(
        Transport $transport,
        Worker $wsWorker,
        Worker $tcpWorker,
        ConnectionRepository $connectionRepository
    )
    {
        $this->wsWorker = $wsWorker;
        $this->tcpWorker = $tcpWorker;
        $this->connectionRepository = $connectionRepository;
        $this->transport = $transport;
    }

    public function onTcpMessage(ConnectionInterface $connection, string $data)
    {
        /** @var SocketEventEntity $eventEntity */
        $eventEntity = unserialize($data);
        $userId = $eventEntity->getUserId();
        // отправляем сообщение пользователю по userId
        try {
            $userConnections = $this->connectionRepository->allByUserId($userId);
            foreach ($userConnections as $userConnection) {
                $this->transport->sendToWebSocket($eventEntity, $userConnection);
                echo
                    'send ' . hash('crc32b', $data) .
                    ' to ' . $userId .
//                    ' ' . FileHelper::sizeFormat(mb_strlen(json_encode($eventEntity->getData()), '8bit')) .
                    PHP_EOL;
            }
        } catch (NotFoundException $e) {
        }
    }
}
