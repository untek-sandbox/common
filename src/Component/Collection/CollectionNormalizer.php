<?php

namespace Untek\Component\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
        return new ArrayCollection($data);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null): bool
    {
        if (!class_exists($type) && !interface_exists($type)) {
            return false;
        }
        return is_subclass_of($type, Collection::class, true) || $type == Collection::class;
    }

    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        /** @var Collection $object */
        return $object->toArray();
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof Collection;
    }
}