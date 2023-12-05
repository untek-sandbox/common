<?php

namespace Untek\Database\Base\Mapping;

use Untek\Model\Entity\Helpers\EntityHelper;

interface MapperInterface
{

    public function serializeEntity(object $entity): array;

    public function restoreEntity(array $item): object;
}