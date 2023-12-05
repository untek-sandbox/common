<?php

namespace Untek\Database\Base\Mapping;

use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Model\Entity\Helpers\EntityHelper;

class DefaultMapper implements MapperInterface
{

    public function __construct(private string $entityClass)
    {
    }

    public function serializeEntity(object $entity): array
    {
        $data = EntityHelper::toArrayForTablize($entity);
        return $data;
    }

    public function restoreEntity(array $item): object
    {
        $entityClass = $this->entityClass;
        $entity = new $entityClass;
        PropertyHelper::setAttributes($entity, $item);
        return $entity;
    }
}