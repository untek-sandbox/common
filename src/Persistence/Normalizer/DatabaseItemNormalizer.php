<?php

namespace Untek\Persistence\Normalizer;

use DateTime;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use function Symfony\Component\String\u;

class DatabaseItemNormalizer extends AbstarctNormalizer implements DbNormalizerInterface
{

    protected function getSerializer(): NormalizerInterface|DenormalizerInterface
    {
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $normalizers = [
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            new ObjectNormalizer($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter()),
        ];
        return new Serializer($normalizers);
    }

    public function denormalize(array $data, string $type): object
    {
        $data = $this->denormalizeTime($data, $type);
        $serializer = $this->getSerializer();
        return $serializer->denormalize($data, $type);
    }
    
    protected function denormalizeTime($data, string $type): array
    {
        foreach ($data as $key => &$value) {
            if (u($key)->endsWith('_at') && is_string($data[$key])) {
                $data[$key] = new DateTime($data[$key]);
            }
        }
        return $data;
    }
}