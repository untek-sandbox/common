<?php

namespace Untek\Component\ObjectNormalizer;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Untek\Component\ObjectNormalizer\Attributes\TypedCollection;

/**
 * @method array getSupportedTypes(?string $format)
 */
class ObjectNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private array $reflectionClassMap;

    /** @var NormalizerInterface[]|DenormalizerInterface[] */
    private array $normalizers = [];
    private ?NameConverterInterface $nameConverter;

    public function __construct(array $normalizers = [], NameConverterInterface $nameConverter = null)
    {
        foreach ($normalizers as $normalizer) {
            if ($normalizer instanceof RootNormalizerAwareInterface) {
                $normalizer->setRootNormalizer($this);
            }
        }
        $this->normalizers = $normalizers;
        $this->nameConverter = $nameConverter;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        $reflection = $this->getReflectionClass($type);
        $target = $reflection->newInstanceWithoutConstructor();

        $properties = [];
        foreach ($reflection->getProperties() as $property) {
            $properties[$property->getName()] = $property;
        }

        foreach ($data as $name => $value) {
            $denormalizedName = $this->denormalizePropertyName($name);
            if(isset($properties[$denormalizedName]) && $value !== null) {
                $property = $properties[$denormalizedName];
                if ($property->isPrivate() || $property->isProtected()) {
                    $property->setAccessible(true);
                }
                $typeName = $property->getType()->getName();
                $value = $this->denormalizeProperty($value, $property);
                $property->setValue($target, $value);
            }
        }
        return $target;
    }

    protected function denormalizePropertyName(string $name): string
    {
        if ($this->nameConverter) {
            $denormalizedName = $this->nameConverter->denormalize($name);
        } else {
            $denormalizedName = $name;
        }
        return $denormalizedName;
    }

    private function denormalizeProperty(mixed $value, \ReflectionProperty $property): mixed
    {
        $typeName = $property->getType()->getName();
        $value = $this->denormalizePropertyAttributes($value, $property);
        if (empty($this->normalizers)) {
            return $value;
        }
        foreach ($this->normalizers as $normalizer) {
            $isSupported = $normalizer->supportsDenormalization($value, $typeName);
            if ($isSupported) {
                $value = $normalizer->denormalize($value, $typeName);
            }
        }
        return $value;
    }

    private function denormalizePropertyAttributes(mixed $value, \ReflectionProperty $property): mixed
    {
        if ($property->getAttributes()) {
            foreach ($property->getAttributes() as $attribute) {
                if ($attribute->getName() == TypedCollection::class) {
                    foreach ($attribute->getArguments() as $attributeArgument) {
                        $propertyCollection = [];
                        foreach ($value as $itemValue) {
                            $denormalizedProperty = $this->denormalize($itemValue, $attributeArgument);
                            $propertyCollection[] = $denormalizedProperty;
                        }
                        $value = $propertyCollection;
                    }
                }
            }
        }
        return $value;
    }

    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        $reflection = new \ReflectionObject($object);
        $data = [];
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $normalizedName = $this->normalizePropertyName($propertyName);
            if ($property->isInitialized($object)) {
                $value = $property->getValue($object);
                $value = $this->normalizeProperty($value, $property);
                if (is_array($value)) {
                    foreach ($value as $itemName => $itemValue) {
                        if (is_object($itemValue)) {
                            $value[$itemName] = $this->normalize($itemValue);
                        }
                    }
                }
                $data[$normalizedName] = $value;
            }
        }
        return $data;
    }

    protected function normalizePropertyName(string $name): string
    {
        if ($this->nameConverter) {
            $normalizedName = $this->nameConverter->normalize($name);
        } else {
            $normalizedName = $name;
        }
        return $normalizedName;
    }

    private function normalizeProperty(mixed $value, \ReflectionProperty $property): mixed
    {
        if (empty($this->normalizers)) {
            return $value;
        }
        $typeName = $property->getType()->getName();
        foreach ($this->normalizers as $normalizer) {
            $isSupported = $normalizer->supportsNormalization($value, $typeName);
            if ($isSupported) {
                $value = $normalizer->normalize($value, $typeName);
            }
        }
        return $value;
    }

    private function getReflectionClass($className)
    {
        if (!isset($this->reflectionClassMap[$className])) {
            $this->reflectionClassMap[$className] = new \ReflectionClass($className);
        }
        return $this->reflectionClassMap[$className];
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null): bool
    {
        return class_exists($type);
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return is_object($data);
    }
}