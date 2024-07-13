<?php

namespace Untek\Database\Base\Hydrator\Traits;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Untek\Database\Base\Hydrator\DatabaseItemNormalizer;

trait NormalizerTrait
{

    abstract public function getClassName(): string;

    protected function getNormalizer(): NormalizerInterface|DenormalizerInterface
    {
        return new DatabaseItemNormalizer();
    }

    protected function normalize(object $entity): array
    {
        return $this->getNormalizer()->normalize($entity);
    }

    protected function denormalize(array $item): object
    {
        return $this->getNormalizer()->denormalize($item, $this->getClassName());
    }

    protected function denormalizeCollection(array $data): array
    {
        foreach ($data as $key => $item) {
            $data[$key] = $this->denormalize((array)$item);
        }
        return $data;
    }
}