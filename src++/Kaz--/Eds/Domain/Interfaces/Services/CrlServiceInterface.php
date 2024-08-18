<?php

namespace Untek\Kaz\Eds\Domain\Interfaces\Services;

use Untek\Domain\Service\Interfaces\CrudServiceInterface;
use Untek\Kaz\Eds\Domain\Entities\LogEntity;

interface CrlServiceInterface extends CrudServiceInterface
{

    public function refreshCountByHostId(int $hostId): int;

    public function refreshByHostId(int $hostId): LogEntity;
}

