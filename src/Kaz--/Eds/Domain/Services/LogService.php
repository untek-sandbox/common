<?php

namespace Untek\Kaz\Eds\Domain\Services;

use Untek\Kaz\Eds\Domain\Interfaces\Services\LogServiceInterface;
use Untek\Domain\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Kaz\Eds\Domain\Interfaces\Repositories\LogRepositoryInterface;
use Untek\Domain\Service\Base\BaseCrudService;
use Untek\Kaz\Eds\Domain\Entities\LogEntity;

/**
 * @method LogRepositoryInterface getRepository()
 */
class LogService extends BaseCrudService implements LogServiceInterface
{

    public function __construct(EntityManagerInterface $em)
    {
        $this->setEntityManager($em);
    }

    public function getEntityClass() : string
    {
        return LogEntity::class;
    }


}

