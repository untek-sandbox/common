<?php

namespace Untek\Component\ValueObject;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @method array getSupportedTypes(?string $format)
 */
class ValueObjectNormalizer implements NormalizerInterface, DenormalizerInterface
{

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        return new $type($data);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null): bool
    {
        if (!class_exists($type)) {
            return false;
        }
        return is_subclass_of($type, ValueObjectInterface::class, true);
    }

    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        /** @var ValueObjectInterface $object */
        return $object->get();
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof ValueObjectInterface;
    }
}