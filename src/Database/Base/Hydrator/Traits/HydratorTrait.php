<?php

namespace Untek\Database\Base\Hydrator\Traits;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Database\Base\Hydrator\DatabaseItemNormalizer;

DeprecateHelper::hardThrow();

trait HydratorTrait
{

    abstract public function getClassName(): string;

    /**
     * @param object $entity
     * @return array
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @todo rename to normalize
     */
    /*protected function dehydrate(object $entity): array
    {
        return $this->getNormalizer()->normalize($entity);
    }*/

    /**
     * @param array $item
     * @return object
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @todo rename to denormalize
     */
    /*protected function hydrate(array $item): object
    {
        return $this->getNormalizer()->denormalize($item, $this->getClassName());
    }*/

    /**
     * @param array $data
     * @return array
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @todo rename to denormalizeCollection
     */
    /*protected function hydrateCollection(array $data): array
    {
        foreach ($data as $key => $item) {
            $data[$key] = $this->hydrate((array)$item);
        }
        return $data;
    }*/
}