<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Services;

use Untek\Domain\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Domain\Service\Base\BaseService;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\Repositories\VarRepositoryInterface;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\Services\VarServiceInterface;

/**
 * @method VarRepositoryInterface getRepository()
 */
class VarService extends BaseService implements VarServiceInterface
{

    public function __construct(EntityManagerInterface $em, VarRepositoryInterface $varRepository)
    {
        $this->setEntityManager($em);
        $this->setRepository($varRepository);
    }

    public function getEntityClass(): string
    {
        return null;
    }

    public function process(string $value): string
    {
        return $this->getRepository()->process($value);
    }

    public function processList(array $list): array
    {
        return $this->getRepository()->processList($list);
    }

    public function set(string $key, $value): void
    {
        $this->getRepository()->set($key, $value);
    }

    public function setList(array $list): void
    {
        $this->getRepository()->setList($list);
    }

    public function get(string $key, $default = null)
    {
        return $this->getRepository()->get($key, $default);
    }
}
