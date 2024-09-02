<?php

namespace Untek\Persistence\Normalizer;

use DateTime;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Untek\Component\Collection\CollectionNormalizer;
use Untek\Component\ValueObject\ValueObjectNormalizer;
use function Symfony\Component\String\u;

class DatabaseItemNormalizer extends AbstarctNormalizer implements DbNormalizerInterface
{

    protected function getSerializer(): NormalizerInterface|DenormalizerInterface
    {
        $defaultContext = [
            /*AbstractNormalizer::IGNORED_ATTRIBUTES => [
                'id'
            ],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER =>
                function ($articles, $format, $context)  {
                    return $articles->getId();
                }*/
        ];
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader(/*new AnnotationReader()*/));
        $propertyTypeExtractor = new ReflectionExtractor();
        $objectNormalizer = new ObjectNormalizer(
            classMetadataFactory: $classMetadataFactory,
//            propertyTypeExtractor: $propertyTypeExtractor,
            nameConverter: new CamelCaseToSnakeCaseNameConverter(),
            defaultContext: $defaultContext,
        );
        $normalizers = [
//            new PropertyNormalizer(),
            new DateTimeNormalizer(),
            new ValueObjectNormalizer(),
            new CollectionNormalizer(),
            new BackedEnumNormalizer(),
            new UidNormalizer([
                UidNormalizer::NORMALIZATION_FORMAT_KEY => UidNormalizer::NORMALIZATION_FORMAT_BASE58,
            ]),
            new ArrayDenormalizer(),
            $objectNormalizer,
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