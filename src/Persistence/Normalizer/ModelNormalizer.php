<?php

namespace Untek\Persistence\Normalizer;

use DateTime;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Symfony\Component\Serializer\Serializer;
use Untek\Component\Collection\CollectionNormalizer;
use Untek\Component\ValueObject\ValueObjectNormalizer;
use function Symfony\Component\String\u;

class ModelNormalizer extends AbstarctNormalizer implements DbNormalizerInterface
{

    protected function getSerializer(): NormalizerInterface|DenormalizerInterface
    {
        $normalizers = [
            new DateTimeNormalizer(),
            new ValueObjectNormalizer(),
            new CollectionNormalizer(),
            new BackedEnumNormalizer(),
            new UidNormalizer([
                UidNormalizer::NORMALIZATION_FORMAT_KEY => UidNormalizer::NORMALIZATION_FORMAT_BASE58,
            ],
            ),
        ];
        return new \Untek\Component\ObjectNormalizer\ObjectNormalizer($normalizers, new CamelCaseToSnakeCaseNameConverter());
    }
}