<?php

namespace Untek\Database\Base\Hydrator\Traits;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
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

    /**
     * @return NormalizerInterface|DenormalizerInterface|null
     */
    protected function getNormalizer()//: null|NormalizerInterface|DenormalizerInterface
    {
        return null;
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