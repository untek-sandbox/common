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
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use function Symfony\Component\String\u;

class DatabaseItemNormalizer implements DbNormalizerInterface
{

    protected function getSerializer(): SerializerInterface
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

    protected function ignoreFields(): array
    {
        return [];
    }

    protected function onlyFields(): array
    {
        return [];
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

    public function normalize(object $object): array
    {
        $serializer = $this->getSerializer();
        $context = [];
        $ignoreFields = $this->ignoreFields();
        /*if ($ignoreFields) {
            $context[AbstractNormalizer::IGNORED_ATTRIBUTES] = $ignoreFields;
        }
        $onlyFields = $this->onlyFields();
        if ($onlyFields) {
            $context[AbstractNormalizer::ATTRIBUTES] = $onlyFields;
        }*/
        $normalized = $serializer->normalize($object, null, $context);
        $normalized = $this->removeIgnoreFields($normalized);
        return $normalized;
    }

    protected function removeIgnoreFields(array $normalized): array
    {
        $ignoreFields = $this->ignoreFields();
        if ($ignoreFields) {
            foreach ($ignoreFields as $field) {
                unset($normalized[$field]);
            }
        }
        return $normalized;
    }
}