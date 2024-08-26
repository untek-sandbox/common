<?php

namespace Untek\Tests\Persistence\Hydrator\Fixture\Normalizer;

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

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null)
    {
        return in_array($type, [DateTimeInterface::class, \DateTime::class, \DateTimeImmutable::class]);
    }

    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        /** @var DateTimeInterface $object */
        return $object->format(DateTimeInterface::ATOM);
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return $data instanceof DateTimeInterface;
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method array getSupportedTypes(?string $format)
    }
}