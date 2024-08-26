<?php

namespace Untek\Tests\Persistence\ObjectNormalizer\Fixture\Normalizer;

use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @method array getSupportedTypes(?string $format)
 */
class CustomDateTimeNormalizer implements NormalizerInterface, DenormalizerInterface
{

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        return new DateTimeImmutable($data);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null): bool
    {
        return is_subclass_of($type, DateTimeInterface::class, true);
    }

    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        /** @var DateTimeInterface $object */
        return $object->format(DateTimeInterface::ATOM);
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return $data instanceof DateTimeInterface;
    }
}