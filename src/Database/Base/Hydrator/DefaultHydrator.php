<?php

namespace Untek\Database\Base\Hydrator;

use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Model\Entity\Helpers\EntityHelper;

class DefaultHydrator implements HydratorInterface
{

    public function __construct(private string $entityClass)
    {
    }

    public function dehydrate(object $entity): array
    {
        $data = EntityHelper::toArrayForTablize($entity);
        return $data;
    }

    public function hydrate(array $item, object $entity = null): object
    {
        if($entity == null) {
            $entityClass = $this->entityClass;
            $entity = new $entityClass;
        }
        PropertyHelper::setAttributes($entity, $item);
        return $entity;
    }
}