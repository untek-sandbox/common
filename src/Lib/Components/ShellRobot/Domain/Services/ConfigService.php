<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Services;

use Untek\Domain\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Domain\Service\Base\BaseService;
use Untek\Lib\Components\ShellRobot\Domain\Factories\ShellFactory;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\Repositories\ConfigRepositoryInterface;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\Services\ConfigServiceInterface;

/**
 * @method ConfigRepositoryInterface getRepository()
 */
class ConfigService extends BaseService implements ConfigServiceInterface
{

    public function __construct(EntityManagerInterface $em, ConfigRepositoryInterface $configRepository)
    {
        $this->setEntityManager($em);
        $this->setRepository($configRepository);
    }

    public function getEntityClass(): string
    {
        return ConfigEntity::class;
    }

    public function get(string $key, $default = null)
    {
        $value = $this->getRepository()->get($key, $default);
        if (is_string($value)) {
            $value = ShellFactory::getVarProcessor()->process($value);
        }
        return $value;
    }
}
