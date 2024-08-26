<?php

namespace Untek\Component\Hydrator;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use function Symfony\Component\String\u;

/**
 * @method array getSupportedTypes(?string $format)
 */
class Hydrator implements NormalizerInterface, DenormalizerInterface
{
    private array $reflectionClassMap;

    /** @var NormalizerInterface[]|DenormalizerInterface[] */
    private array $normalizers = [];

    public function __construct(array $normalizers = [])
    {
        foreach ($normalizers as $normalizer) {
            if ($normalizer instanceof HydratorAwareInterface) {
                $normalizer->setHydrator($this);
            }
        }
        $this->normalizers = $normalizers;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        $reflection = $this->getReflectionClass($type);
        $target = $reflection->newInstanceWithoutConstructor();
        foreach ($data as $name => $value) {
            $nameCamelCase = u($name)->camel();
            $property = $reflection->getProperty($nameCamelCase);
            if ($property->isPrivate() || $property->isProtected()) {
                $property->setAccessible(true);
            }
            $typeName = $property->getType()->getName();
//            if(!$property->getType()->isBuiltin()) {
            $value = $this->denormalizeAttribute($value, $typeName);
//            }
            $property->setValue($target, $value);
        }
        return $target;
    }

    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        $reflection = new \ReflectionObject($object);
        $data = [];
        foreach ($reflection->getProperties() as $property) {
            $typeName = $property->getName();
            $value = $property->getValue($object);
            $value = $this->normalizeAttribute($value, $typeName);
            if (is_array($value)) {
                foreach ($value as $itemName => $itemValue) {
                    if (is_object($itemValue)) {
                        $value[$itemName] = $this->normalize($itemValue);
                    }
                }
            }
            $data[$typeName] = $value;
        }
        return $data;
    }

    private function normalizeAttribute(mixed $value, string $typeName): mixed
    {
        if (empty($this->normalizers)) {
            return $value;
        }
        /** @var NormalizerInterface|DenormalizerInterface $typeHandler */
        $typeHandler = $this->normalizers[$typeName] ?? null;
        if ($typeHandler) {
            $value = $typeHandler->normalize($value, $typeName);
        } else {
            foreach ($this->normalizers as $normalizer) {
                $isSupported = $normalizer->supportsNormalization($value, $typeName);
                if ($isSupported) {
                    $value = $normalizer->normalize($value, $typeName);
                }
            }
        }
        return $value;
    }

    private function denormalizeAttribute(mixed $value, string $typeName): mixed
    {
        if (empty($this->normalizers)) {
            return $value;
        }
        /** @var NormalizerInterface|DenormalizerInterface $typeHandler */
        $typeHandler = $this->normalizers[$typeName] ?? null;
        if ($typeHandler) {
            $value = $typeHandler->denormalize($value, $typeName);
        } else {
            foreach ($this->normalizers as $normalizer) {
                $isSupported = $normalizer->supportsDenormalization($value, $typeName);
                if ($isSupported) {
                    $value = $normalizer->denormalize($value, $typeName);
                }
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

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null)
    {
        return class_exists($type);
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        return is_object($data);
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method array getSupportedTypes(?string $format)
    }
}