<?php

namespace Untek\Component\Collection;

use Doctrine\Common\Collections\Collection;
use ReflectionClass;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Untek\Component\ObjectNormalizer\RootNormalizerAwareInterface;

/**
 * @method array getSupportedTypes(?string $format)
 */
class CollectionNormalizer implements NormalizerInterface, DenormalizerInterface, RootNormalizerAwareInterface
{

    private NormalizerInterface|DenormalizerInterface $rootNormalizer;

    public function setRootNormalizer(NormalizerInterface|DenormalizerInterface $rootNormalizer): void
    {
        $this->rootNormalizer = $rootNormalizer;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        $list = [];
        foreach ($data as $item) {
            $list[] = $this->rootNormalizer->denormalize($item, $type::getClass());
        }
        return new $type($list);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null)
    {
        if (!class_exists($type)) {
            return false;
        }
        $reflection = new ReflectionClass($type);
        return array_key_exists(Collection::class, $reflection->getInterfaces());
    }

    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        /** @var Collection $object */
        return $object->toArray();
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return $data instanceof Collection;
    }
}