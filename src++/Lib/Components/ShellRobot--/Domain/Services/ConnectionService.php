<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Services;

use Untek\Lib\Components\ShellRobot\Domain\Entities\HostEntity;
use Untek\Lib\Components\ShellRobot\Domain\Enums\VarEnum;
use Untek\Lib\Components\ShellRobot\Domain\Factories\ShellFactory;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\Services\ConnectionServiceInterface;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\Repositories\ConnectionRepositoryInterface;
use Untek\Domain\Service\Base\BaseService;
use Untek\Domain\EntityManager\Interfaces\EntityManagerInterface;

/**
 * @method ConnectionRepositoryInterface getRepository()
 */
class ConnectionService extends BaseService implements ConnectionServiceInterface
{
    
    const DEFAULT_CONNECTION_NAME = 'default';

    public function __construct(EntityManagerInterface $em, ConnectionRepositoryInterface $connectionRepository)
    {
        $this->setEntityManager($em);
        $this->setRepository($connectionRepository);
    }

    public function getEntityClass() : string
    {
        return ConnectionEntity::class;
    }

    public function get(?string $connectionName = null)
    {
        $connectionName = $connectionName ?: $this->getCurrentConnectionName();
        return $this->getRepository()->get($connectionName);
    }

    public function oneByName(?string $connectionName = null): HostEntity
    {
        $connectionName = $connectionName ?: $this->getCurrentConnectionName();
        return $this->getRepository()->oneByName($connectionName);
    }

    /*public function getCurrent()
    {
        return $this->getRepository()->getCurrent();
    }*/

    public function getCurrent()
    {
        $connectionName = $this->getCurrentConnectionName();
        return $this->get($connectionName);
    }

    private function getCurrentConnectionName()
    {
        return ShellFactory::getVarProcessor()->get(VarEnum::CURRENT_CONNECTION, self::DEFAULT_CONNECTION_NAME);
    }
}
