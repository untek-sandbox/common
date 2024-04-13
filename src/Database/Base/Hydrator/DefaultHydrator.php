<?php

namespace Untek\Database\Base\Hydrator;

class DefaultHydrator extends AbstractHydrator implements HydratorInterface
{

    public function __construct(private string $entityClass)
    {
    }

    public function dehydrate(object $entity): array
    {
        return $this->getNormalizer()->normalize($entity);
    }

    public function hydrate(array $item, object $entity = null): object
    {
        return $this->getNormalizer()->denormalize($item, $this->entityClass);
    }
}