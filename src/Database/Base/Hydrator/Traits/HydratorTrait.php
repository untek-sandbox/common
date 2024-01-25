<?php

namespace Untek\Database\Base\Hydrator\Traits;

use Untek\Database\Base\Hydrator\DefaultHydrator;
use Untek\Database\Base\Hydrator\HydratorInterface;

trait HydratorTrait
{

    private HydratorInterface $hydrator;

    protected function getHydrator(): HydratorInterface
    {
        if (isset($this->hydrator)) {
            return $this->hydrator;
        }
        return new DefaultHydrator($this->getClassName());
    }

    protected function dehydrate(object $entity): array
    {
        $data = $this->getHydrator()->dehydrate($entity);
        return $data;
    }

    protected function hydrate(array $item): object
    {
        $entity = $this->getHydrator()->hydrate($item);
        return $entity;
    }

    protected function hydrateCollection(array $data): array {
        foreach ($data as $key => $item) {
            $data[$key] = $this->hydrate((array) $item);
        }
        return $data;
    }
}