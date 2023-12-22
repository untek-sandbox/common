<?php

namespace Untek\Framework\Socket\Domain\Libs\Handlers;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Workerman\Connection\ConnectionInterface;
use Workerman\Worker;
use Untek\Core\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use Untek\Framework\Socket\Domain\Entities\SocketEventEntity;
use Untek\Framework\Socket\Domain\Enums\SocketEventEnum;
use Untek\Framework\Socket\Domain\Libs\Transport;
use Untek\Framework\Socket\Domain\Repositories\Ram\ConnectionRepository;
use Untek\User\Authentication\Domain\Interfaces\Services\AuthServiceInterface;

class WsHandler
{

    private $tcpWorker;
    private $transport;
    private $wsWorker;
    private $localUrl = 'tcp://127.0.0.1:1234';
    private $connectionRepository;
//    private $authService;

    public function __construct(
        Transport $transport,
        Worker $wsWorker, 
        Worker $tcpWorker,
        ConnectionRepository $connectionRepository,
        private UserProviderInterface $userProvider
//        AuthServiceInterface $authService
    )
    {
        $this->wsWorker = $wsWorker;
        $this->tcpWorker = $tcpWorker;
//        $this->authService = $authService;
        $this->connectionRepository = $connectionRepository;
        $this->transport = $transport;
    }
    
    public function onWsStart()
    {
        $this->tcpWorker->listen();
    }

    public function onWsConnect(ConnectionInterface $connection)
    {
        $connection->onWebSocketConnect = function ($connection) {
            $userId = $this->auth($_GET);
            // при подключении нового пользователя сохраняем get-параметр, который же сами и передали со страницы сайта
            $this->connectionRepository->addConnection($userId, $connection);
            // вместо get-параметра можно также использовать параметр из cookie, например $_COOKIE['PHPSESSID']

            $event = new SocketEventEntity;
            $event->setUserId($userId);
            $event->setName(SocketEventEnum::CONNECT);
            $event->setData([
                'totalConnections' => $this->connectionRepository->countByUserId($userId),
            ]);
            $this->transport->sendToWebSocket($event, $connection);
        };
    }

    public function onWsClose(ConnectionInterface $connection)
    {
        $this->connectionRepository->remove($connection);
    }

    public function onWsMessage(ConnectionInterface $connection,  $jsonMessage)
    {
        $data = json_decode($jsonMessage, JSON_OBJECT_AS_ARRAY);
        $event = new SocketEventEntity;
        $event->setUserId($data['toAddress']);
        $event->setName('cryptoMessage.p2p');
        $event->setData([
            'document' => $data['document'],
        ]);
        $this->transport->sendMessageToTcp($event);
    }

    protected function auth($params)
    {
        $credentials = $params['token'] ?? null;
        if (!empty($credentials)) {
            /** @var IdentityEntityInterface $identityEntity */
//            $identityEntity = $this->authService->authenticationByToken($credentials);
            $identityEntity = $this->userProvider->loadUserByIdentifier($credentials);
            return $identityEntity->getId();
        }
        throw new AuthenticationException('Empty user id');
    }
}
