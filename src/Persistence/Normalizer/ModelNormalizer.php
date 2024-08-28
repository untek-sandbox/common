<?php

namespace Untek\Persistence\Normalizer;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Untek\Component\Collection\EntityCollectionNormalizer;
use Untek\Component\ObjectNormalizer\ObjectNormalizer;
use Untek\Component\ValueObject\ValueObjectNormalizer;

class ModelNormalizer extends AbstarctNormalizer implements DbNormalizerInterface
{

    protected function getSerializer(): NormalizerInterface|DenormalizerInterface
    {
        $normalizers = [
            new DateTimeNormalizer(),
            new EntityCollectionNormalizer(),
            new BackedEnumNormalizer(),
            new UidNormalizer([
                UidNormalizer::NORMALIZATION_FORMAT_KEY => UidNormalizer::NORMALIZATION_FORMAT_BASE58,
            ]),
            new ValueObjectNormalizer(),
        ];
        return new ObjectNormalizer($normalizers, new CamelCaseToSnakeCaseNameConverter());
    }
}