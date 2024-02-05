<?php

namespace Untek\Database\Base\Hydrator\Traits;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Untek\Database\Base\Hydrator\DatabaseItemNormalizer;
use Untek\Database\Base\Hydrator\DefaultHydrator;
use Untek\Database\Base\Hydrator\HydratorInterface;

trait HydratorTrait
{

    protected function getHydrator(): HydratorInterface
    {
        return new DefaultHydrator($this->getClassName());
    }

    protected function getNormalizer(): NormalizerInterface|DenormalizerInterface
    {
        return new DatabaseItemNormalizer();
    }

    protected function dehydrate(object $entity): array
    {
        if ($this->getNormalizer()) {
            return $this->getNormalizer()->normalize($entity);
        } else {
            $data = $this->getHydrator()->dehydrate($entity);
            return $data;
        }
    }

    protected function hydrate(array $item): object
    {
        if ($this->getNormalizer()) {
            return $this->getNormalizer()->denormalize($item, $this->getClassName());
        } else {
            $entity = $this->getHydrator()->hydrate($item);
            return $entity;
        }
    }

    protected function hydrateCollection(array $data): array
    {
        foreach ($data as $key => $item) {
            $data[$key] = $this->hydrate((array)$item);
        }
        return $data;
    }
}