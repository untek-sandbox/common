<?php

namespace Untek\Framework\Socket\Infrastructure\Services;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\Model\Entity\Helpers\EntityHelper;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use Workerman\Connection\ConnectionInterface;
use Workerman\Worker;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Core\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use Untek\Framework\Socket\Infrastructure\Dto\SocketEvent;
use Untek\Framework\Socket\Domain\Enums\SocketEventEnum;
use Untek\Framework\Socket\Infrastructure\Storage\ConnectionRamStorage;
use Untek\User\Authentication\Domain\Interfaces\Services\AuthServiceInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\User\Authentication\Domain\Authentication\Token\ApiToken;
use Untek\Framework\Socket\Domain\Interfaces\Services\ClientMessageHandlerInterface;

class SocketDaemonTest extends SocketDaemon
{

    private $users = [];
    private $tcpWorker;
    private $wsWorker;

    public function __construct(
        private ConnectionRamStorage $connectionRepository,
        private TokenServiceInterface $tokenService,
        private ClientMessageHandlerInterface $clientMessageHanler,
        private string $localUrl,
        private string $clientUrl,
        private ?string $mode = null
    )
    {
    }

    public function sendMessageToTcp(SocketEvent $eventEntity)
    {
        // todo: write event to file
    }

    public function onWsStart()
    {
        
    }

    protected function auth($params)//: int
    {
        
    }

    public function onWsConnect(ConnectionInterface $connection)
    {
        
    }

    public function onMessage(ConnectionInterface $connection, $data) 
    {
    }

    protected function sendConnectEventToClient($userId) {
        
    }

    public function onWsClose(ConnectionInterface $connection)
    {
        
    }

    public function onTcpMessage(ConnectionInterface $connection, string $data)
    {
        
    }

    public function runAll(bool $daemonize = false)
    {
        
    }

    private function sendToWebSocket(SocketEvent $socketEvent, ConnectionInterface $connection)
    {
        
    }
}
