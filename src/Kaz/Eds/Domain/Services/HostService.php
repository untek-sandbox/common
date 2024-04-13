<?php

namespace Untek\Kaz\Eds\Domain\Services;

use Untek\Kaz\Eds\Domain\Interfaces\Services\HostServiceInterface;
use Untek\Domain\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Kaz\Eds\Domain\Interfaces\Repositories\HostRepositoryInterface;
use Untek\Domain\Service\Base\BaseCrudService;
use Untek\Kaz\Eds\Domain\Entities\HostEntity;

/**
 * @method HostRepositoryInterface getRepository()
 */
class HostService extends BaseCrudService implements HostServiceInterface
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->setEntityManager($em);
    }

    public function getEntityClass() : string
    {
        return HostEntity::class;
    }


}

