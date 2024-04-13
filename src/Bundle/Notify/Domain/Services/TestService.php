<?php

namespace Untek\Bundle\Notify\Domain\Services;

use Untek\Bundle\Notify\Domain\Interfaces\Services\TestServiceInterface;
use Untek\Bundle\Notify\Domain\Interfaces\Repositories\TestRepositoryInterface;
use Untek\Model\Service\Base\BaseCrudService;

class TestService extends BaseCrudService implements TestServiceInterface
{

    public function __construct(TestRepositoryInterface $repository)
    {
        $this->setRepository($repository);
    }
    
}
