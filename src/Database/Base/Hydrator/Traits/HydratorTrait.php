<?php

namespace Untek\Database\Base\Hydrator\Traits;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Untek\Database\Base\Hydrator\DatabaseItemNormalizer;
//use Untek\Database\Base\Hydrator\DefaultHydrator;
//use Untek\Database\Base\Hydrator\HydratorInterface;

trait HydratorTrait
{

    abstract public function getClassName(): string;

    /*protected function getHydrator(): HydratorInterface
    {
        return new DefaultHydrator($this->getClassName());
    }*/

    /*protected function getNormalizer(): NormalizerInterface|DenormalizerInterface
    {
        return new DatabaseItemNormalizer();
    }*/

    /**
     * @param object $entity
     * @return array
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @todo rename to normalize
     */
    protected function dehydrate(object $entity): array
    {
        return $this->getNormalizer()->normalize($entity);
        /*if(!($this->getHydrator() instanceof DefaultHydrator)) {
            return $this->getHydrator()->dehydrate($entity);
        } else {
            return $this->getNormalizer()->normalize($entity);
        }*/
    }

    /**
     * @param array $item
     * @return object
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @todo rename to denormalize
     */
    protected function hydrate(array $item): object
    {
        return $this->getNormalizer()->denormalize($item, $this->getClassName());
        /*if(!($this->getHydrator() instanceof DefaultHydrator)) {
            return $this->getHydrator()->hydrate($item);
        } else {
            return $this->getNormalizer()->denormalize($item, $this->getClassName());
        }*/
    }

    /**
     * @param array $data
     * @return array
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @todo rename to denormalizeCollection
     */
    protected function hydrateCollection(array $data): array
    {
        foreach ($data as $key => $item) {
            $data[$key] = $this->hydrate((array)$item);
        }
        return $data;
    }
}