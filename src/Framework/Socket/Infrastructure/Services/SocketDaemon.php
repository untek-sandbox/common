<?php

namespace Untek\Framework\Socket\Infrastructure\Services;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Framework\Socket\Application\Services\SocketDaemonInterface;
use Untek\Framework\Socket\Domain\Enums\SocketEventEnum;
use Untek\Framework\Socket\Domain\Interfaces\Services\ClientMessageHandlerInterface;
use Untek\Framework\Socket\Infrastructure\Dto\SocketEvent;
use Untek\Framework\Socket\Infrastructure\Storage\ConnectionRamStorage;
use Untek\Model\Entity\Helpers\EntityHelper;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Workerman\Connection\ConnectionInterface;
use Workerman\Worker;

class SocketDaemon implements SocketDaemonInterface
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
        // массив для связи соединения пользователя и необходимого нам параметра

        // создаём ws-сервер, к которому будут подключаться все наши пользователи
        $this->wsWorker = new Worker($clientUrl);
        // создаём обработчик, который будет выполняться при запуске ws-сервера
        $this->wsWorker->onWorkerStart = [$this, 'onWsStart'];
        $this->wsWorker->onConnect = [$this, 'onWsConnect'];
        $this->wsWorker->onClose = [$this, 'onWsClose'];
        $this->wsWorker->onMessage = [$this, 'onMessage'];
    }

    public function sendMessageToTcp(SocketEvent $eventEntity)
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
        $credentials = $params['token'] ?? null;
        if (empty($credentials)) {
            throw new AuthenticationException('Bad credentials.');
        }
        return $this->tokenService->getIdentityIdByToken($credentials);
    }

    public function onWsConnect(ConnectionInterface $connection)
    {
        $connection->onWebSocketConnect = function ($connection) {
            $userId = $this->auth($_GET);
            // при подключении нового пользователя сохраняем get-параметр, который же сами и передали со страницы сайта
            $this->connectionRepository->addConnection($userId, $connection);
            // вместо get-параметра можно также использовать параметр из cookie, например $_COOKIE['PHPSESSID']

//           $this->sendConnectEventToClient($userId);
        };
    }

    public function onMessage(ConnectionInterface $connection, $data)
    {
        $userId = $this->connectionRepository->userIdByConnection($connection);
        //print_r(['$userId' , $userId]);
        //echo "Received message: $data  \n";
        $decoded = json_decode($data, true);
        $decoded['userId'] = $userId;

        $message = $this->clientMessageHanler->onMessage($decoded);

        if ($message || $this->mode == 'dev') {
            $event = new SocketEvent();
            $event->setUserId($userId);
            $event->setName(SocketEventEnum::CLIENT_MESSAGE_RECEIVED);
            $event->setPayload([
                'data' => $data,
            ]);

            $this->sendToWebSocket($event, $connection);
        }

    }

    protected function sendConnectEventToClient($userId)
    {
        $event = new SocketEvent();
        $event->setUserId($userId);
        $event->setName(SocketEventEnum::CONNECT);
        $event->setPayload([
            'totalConnections' => $this->connectionRepository->countByUserId($userId),
        ]);
        $this->sendToWebSocket($event, $connection);
    }

    public function onWsClose(ConnectionInterface $connection)
    {
        $this->connectionRepository->remove($connection);
    }

    public function onTcpMessage(ConnectionInterface $connection, string $data)
    {
        /** @var SocketEvent $eventEntity */
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

    public function runAll(bool $daemonize = false)
    {
        Worker::$daemonize = $daemonize;
        Worker::runAll();
    }

    private function sendToWebSocket(SocketEvent $socketEvent, ConnectionInterface $connection)
    {
        $event = EntityHelper::toArray($socketEvent);
        $json = json_encode([
            'name' => $socketEvent->getName(),
            'payload' => $socketEvent->getPayload(),
        ]);
        $connection->send($json);
    }
}
