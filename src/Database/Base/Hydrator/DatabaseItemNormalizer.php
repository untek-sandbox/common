<?php

namespace Untek\Database\Base\Hydrator;

use ArrayObject;
use DateTimeImmutable;
use DateTime;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Untek\Core\Text\Helpers\Inflector;

/**
 * @method array getSupportedTypes(?string $format)
 */
class DatabaseItemNormalizer implements DenormalizerInterface, NormalizerInterface
{

    protected function getSerializer(): SerializerInterface
    {
        $normalizers = [
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter()),
        ];
        return new Serializer($normalizers);
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        $data = $this->denormalizeTime($data, $type);
        $serializer = $this->getSerializer();
        return $serializer->denormalize($data, $type, $format, $context);
    }

    protected function relationFields(): array
    {
        return [];
    }

    protected function denormalizeTime($data, string $type): array
    {
        foreach ($data as $key => &$value) {
//            $r = new \ReflectionMethod("$type::get".Inflector::camelize($key));
//            $isTime = is_subclass_of($r->getReturnType(), \DateTimeInterface::class);
            $isTime = $value && is_string($value);
            if ($isTime) {
                $denormalized = DateTime::createFromFormat(DateTime::RFC3339, $value);
                if ($denormalized) {
                    $value = $denormalized;
                }
            }
        }
        return $data;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        $serializer = $this->getSerializer();
        return $serializer->supportsDenormalization($data, $type, $format);
    }

    public function normalize(mixed $object, string $format = null, array $context = []): float|array|ArrayObject|bool|int|string|null
    {
        $serializer = $this->getSerializer();
        $normalized = $serializer->normalize($object, $format, $context);
        $normalized = $this->removeRelationFields($normalized);
        return $normalized;
    }

    protected function removeRelationFields(array $normalized): array
    {
        if ($this->relationFields()) {
            foreach ($this->relationFields() as $field) {
                unset($normalized[$field]);
            }
        }
        return $normalized;
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        $serializer = $this->getSerializer();
        return $serializer->supportsNormalization($data, $format);
    }
}