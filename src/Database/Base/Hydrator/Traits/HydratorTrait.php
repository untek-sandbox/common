<?php

namespace Untek\Database\Base\Hydrator\Traits;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Untek\Database\Base\Hydrator\DatabaseItemNormalizer;
use Untek\Database\Base\Hydrator\DefaultHydrator;
use Untek\Database\Base\Hydrator\HydratorInterface;

trait HydratorTrait
{

    abstract public function getClassName(): string;

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
        if(!($this->getHydrator() instanceof DefaultHydrator)) {
            return $this->getHydrator()->dehydrate($entity);
        } else {
            return $this->getNormalizer()->normalize($entity);
        }
    }

    protected function hydrate(array $item): object
    {
        if(!($this->getHydrator() instanceof DefaultHydrator)) {
            return $this->getHydrator()->hydrate($item);
        } else {
            return $this->getNormalizer()->denormalize($item, $this->getClassName());
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