<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Repositories\File;

use Untek\Lib\Components\ShellRobot\Domain\Entities\HostEntity;
use Untek\Lib\Components\ShellRobot\Domain\Enums\VarEnum;
use Untek\Lib\Components\ShellRobot\Domain\Factories\ShellFactory;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\Repositories\ConnectionRepositoryInterface;

class ConnectionRepository implements ConnectionRepositoryInterface
{

//    const DEFAULT_CONNECTION_NAME = 'default';

    public function get(?string $connectionName = null)
    {
//        $connectionName = $connectionName ?: $this->getCurrentConnectionName();
        $connection = ShellFactory::getConfigProcessor()->get("connections.$connectionName");
        return $connection;
    }

    public function oneByName(?string $connectionName = null): HostEntity
    {
        $connection = $this->get($connectionName);
        $hostEntity = $this->createEntity($connection);
        return $hostEntity;
    }

    /*public function getCurrent()
    {
        $connectionName = $this->getCurrentConnectionName();
        return $this->get($connectionName);
    }

    private function getCurrentConnectionName()
    {
        return ShellFactory::getVarProcessor()->get(VarEnum::CURRENT_CONNECTION, self::DEFAULT_CONNECTION_NAME);
    }*/

    private function createEntity(array $connection): HostEntity
    {
        $host = new HostEntity();
        $host->setHost($connection['host'] ?? null);
        $host->setPort($connection['port'] ?? 22);
        $host->setUser($connection['user'] ?? null);
        return $host;
    }
}
