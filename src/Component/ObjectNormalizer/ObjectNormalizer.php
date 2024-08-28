<?php

namespace Untek\Component\ObjectNormalizer;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Type;

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

    protected function denormalizePropertyName(string $name): string
    {
        if ($this->nameConverter) {
            $denormalizedName = $this->nameConverter->denormalize($name);
        } else {
            $denormalizedName = $name;
        }
        return $denormalizedName;
    }
    
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = [])
    {
        $reflection = $this->getReflectionClass($type);
        $target = $reflection->newInstanceWithoutConstructor();

//        $properties = $reflection->getProperties();
//        dd($properties);
        
//        foreach ($properties as $property) {
        foreach ($data as $name => $value) {
//            $name = $property->getName();
            $denormalizedName = $this->denormalizePropertyName($name);

            /*if(!array_key_exists($denormalizedName, $data)) {
                continue;
            }*/
//            $value = $data[$denormalizedName];

            try {
                $property = $reflection->getProperty($denormalizedName);
                if ($property->isPrivate() || $property->isProtected()) {
                    $property->setAccessible(true);
                }
                $typeName = $property->getType()->getName();
                
//            if(!$property->getType()->isBuiltin()) {
                $value = $this->denormalizeProperty($value, $typeName, $property);
//            }
                $property->setValue($target, $value);
            } catch (\Throwable $e) {
//                dd($e);
//                continue;
            }
        }
        return $target;
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
    
    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        $reflection = new \ReflectionObject($object);
        $data = [];
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $normalizedName = $this->normalizePropertyName($propertyName);
            if($property->isInitialized($object)) {
                $value = $property->getValue($object);
                $value = $this->normalizeProperty($value, $propertyName);
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

    private function normalizeProperty(mixed $value, string $typeName): mixed
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

    private function denormalizeProperty(mixed $value, string $typeName, \ReflectionProperty $property): mixed
    {
        if($property->getAttributes()) {
            foreach ($property->getAttributes() as $attribute) {
                if($attribute->getName() == All::class) {
                    foreach ($attribute->getArguments() as $attributeArguments) {
                        foreach ($attributeArguments as $attributeArgument) {
                            if($attributeArgument instanceof Type) {
                                $propertyCollection = [];
                                foreach ($value as $itemValue) {
                                    $denormalizedProperty = $this->denormalize($itemValue, $attributeArgument->type);
                                    $propertyCollection[] = $denormalizedProperty;
                                }
                                return $propertyCollection;
                            }
                        }
                    }
                }
            }
        }
        
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

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null): bool
    {
        return class_exists($type);
    }

    public function supportsNormalization(mixed $data, ?string $format = null): bool
    {
        return is_object($data);
    }
}