<?php

namespace Untek\Framework\Socket\Infrastructure\Storage;

use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Workerman\Connection\ConnectionInterface;

class ConnectionRamStorage {

    // массив для связи соединения пользователя и необходимого нам параметра
    private $connectionTree = [];
    private $reversedConnectionTree = [];

    public function userIdByConnection(ConnectionInterface $connection) {
        $key = $this->generateKey($connection);
        if(!array_key_exists($key, $this->reversedConnectionTree)) {
            throw new NotFoundException();
        }
        return $this->reversedConnectionTree[$key];
    }

    public function allByUserId(/*int*/ $userId) {
        if(!$this->isOnlineByUserId($userId)) {
            return [];
        }
        return $this->connectionTree[$userId];
    }

    public function countByUserId(/*int*/ $userId) {
        if(!$this->isOnlineByUserId($userId)) {
            return 0;
        }
        return count($this->connectionTree[$userId]);
    }

    public function isOnlineByUserId(/*int*/ $userId) {
        return isset($this->connectionTree[$userId]);
    }

    public function addConnection(/*int*/ $userId, ConnectionInterface $connection) {
        $this->connectionTree[$userId][] = $connection;
        $key = $this->generateKey($connection);
        $this->reversedConnectionTree[$key] = $userId;
        echo 'online ' . $this->formatMessage($userId) . PHP_EOL;
    }
    
    private function formatMessage($userId) {
        return '(ID: ' . $userId . ', count online: ' . count($this->connectionTree[$userId]) . ')';
    }

    public function remove(ConnectionInterface $connection) {
        foreach ($this->connectionTree as $userId => $userConnections) {
            foreach ($userConnections as $connectionIndex => $conn) {
                if($connection === $conn) {
                    unset($this->connectionTree[$userId][$connectionIndex]);
                    echo 'offline ' . $this->formatMessage($userId) . PHP_EOL;
                    if(count($userConnections) == 0) {
                        unset($this->connectionTree[$userId]);
                    }
                    return;
                }
            }
        }
//        echo 'not remove ' . PHP_EOL;
    }

    private function generateKey(ConnectionInterface $connection): string {
        return md5(json_encode($connection));
    }
}
