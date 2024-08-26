<?php

namespace Untek\Component\ValueObject;

use ReflectionClass;
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

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null)
    {
        if (!class_exists($type)) {
            return false;
        }
        $reflection = new ReflectionClass($type);
        return array_key_exists(ValueObjectInterface::class, $reflection->getInterfaces());
    }

    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        /** @var ValueObjectInterface $object */
        return $object->get();
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return $data instanceof ValueObjectInterface;
    }
}