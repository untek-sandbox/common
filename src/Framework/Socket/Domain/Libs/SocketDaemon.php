<?php

namespace Untek\Framework\Socket\Domain\Libs;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Untek\Model\Entity\Helpers\EntityHelper;
use Untek\User\Authentication\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use Workerman\Connection\ConnectionInterface;
use Workerman\Worker;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Core\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use Untek\Framework\Socket\Domain\Entities\SocketEventEntity;
use Untek\Framework\Socket\Domain\Enums\SocketEventEnum;
use Untek\Framework\Socket\Domain\Repositories\Ram\ConnectionRepository;
use Untek\User\Authentication\Domain\Interfaces\Services\AuthServiceInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\User\Authentication\Domain\Authentication\Token\ApiToken;

class SocketDaemon
{

    private $users = [];
    private $tcpWorker;
    private $wsWorker;
    private $localUrl = 'tcp://127.0.0.1:1234';
    private $connectionRepository;
//    private $authService;

    public function __construct(
        ConnectionRepository $connectionRepository,
//        AuthServiceInterface $authService,
//        private UserProviderInterface $userProvider,
//        private IdentityRepositoryInterface $identityRepository,
        private TokenServiceInterface $tokenService,
//        private TokenStorageInterface $tokenStorage,
    )
    {
//        $this->authService = $authService;
        $this->connectionRepository = $connectionRepository;
        // массив для связи соединения пользователя и необходимого нам параметра

        // создаём ws-сервер, к которому будут подключаться все наши пользователи
        $this->wsWorker = new Worker("websocket://0.0.0.0:8001");
        // создаём обработчик, который будет выполняться при запуске ws-сервера
        $this->wsWorker->onWorkerStart = [$this, 'onWsStart'];
        $this->wsWorker->onConnect = [$this, 'onWsConnect'];
        $this->wsWorker->onClose = [$this, 'onWsClose'];
    }

    public function sendMessageToTcp(SocketEventEntity $eventEntity)
    {
        // соединяемся с локальным tcp-сервером
        try {
            $instance = stream_socket_client($this->localUrl);
            $serialized = serialize($eventEntity);
            // отправляем сообщение
            fwrite($instance, $serialized . "\n");
        } catch (\Exception $e) {
            return false;
        }
    }

    public function onWsStart()
    {
        // создаём локальный tcp-сервер, чтобы отправлять на него сообщения из кода нашего сайта
        $this->tcpWorker = new Worker($this->localUrl);
        // создаём обработчик сообщений, который будет срабатывать,
        // когда на локальный tcp-сокет приходит сообщение
        $this->tcpWorker->onMessage = [$this, 'onTcpMessage'];
        $this->tcpWorker->listen();
    }

    protected function auth($params)//: int
    {
        /*$userId = intval($params['userId']);
        if (!empty($userId)) {
            return $userId;
        }*/

        $credentials = $params['token'] ?? null;
        if (!empty($credentials)) {
            /** @var IdentityEntityInterface $identityEntity */
//            $identityEntity = $this->authService->authenticationByToken($credentials);
            $userId = $this->tokenService->getIdentityIdByToken($credentials);
            return $userId;
//            $identity = $this->identityRepository->getUserById($userId);
//            return $identity->getId();

//            dd($identity);
//            $token = new ApiToken($identity, 'main', $identity->getRoles(), $credentials);
//            $this->tokenStorage->setToken($token);

//            $identityEntity = $this->userProvider->loadUserByIdentifier($credentials);
//dump($identityEntity);
//            return $identityEntity->getId();
        }

        throw new AuthenticationException('Empty user id');
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
            $this->sendToWebSocket($event, $connection);
        };
    }

    public function onWsClose(ConnectionInterface $connection)
    {
        $this->connectionRepository->remove($connection);
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
                $this->sendToWebSocket($eventEntity, $userConnection);
                echo 'send ' . hash('crc32b', $data) . ' to ' . $userId . PHP_EOL;
            }
        } catch (NotFoundException $e) {
        }
    }

    public function runAll(bool $daemon)
    {
        // Run worker
        if($daemon) {
            Worker::$daemonize = true;
        }
        Worker::runAll();
    }

    private function sendToWebSocket(SocketEventEntity $socketEventEntity, ConnectionInterface $connection)
    {
        $eventArray = EntityHelper::toArray($socketEventEntity);
        $json = json_encode($eventArray);
        $connection->send($json);
    }
}
