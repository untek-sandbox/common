<?php

namespace Untek\Component\Uid;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Ulid;

/**
 * @method array getSupportedTypes(?string $format)
 */
class UidNormalizer implements NormalizerInterface, DenormalizerInterface
{

    public function __construct(private string $defaultFormat)
    {
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        return Ulid::fromString($data);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null): bool
    {
        if (!class_exists($type)) {
            return false;
        }
        return is_subclass_of($type, AbstractUid::class, true);
    }

    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        /** @var AbstractUid $object */
        return $object->toBase58();
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof AbstractUid;
    }
}