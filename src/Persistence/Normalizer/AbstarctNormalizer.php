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
use Untek\Component\ValueObject\ValueObjectNormalizer;
use function Symfony\Component\String\u;

abstract class AbstarctNormalizer implements DbNormalizerInterface
{

    abstract protected function getSerializer(): NormalizerInterface|DenormalizerInterface;
    
    protected function ignoreFields(): array
    {
        return [];
    }

    protected function onlyFields(): array
    {
        return [];
    }

    public function denormalize(array $data, string $type): object
    {
        $serializer = $this->getSerializer();
        return $serializer->denormalize($data, $type);
    }

    public function normalize(object $object): array
    {
        $serializer = $this->getSerializer();
        $context = [];
        /*$ignoreFields = $this->ignoreFields();
        if ($ignoreFields) {
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