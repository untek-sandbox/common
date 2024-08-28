<?php

namespace Untek\Model\Validator;

use Symfony\Component\Validator\Constraint;
use ReflectionClass;

class ValidationConstraintExtractor
{

    private array $reflectionClassMap;
    private array $constraints = [];

    public function extract(object|string $type): array
    {
        if(is_object($type)) {
            $type = get_class($type);
        }
        if(!isset($this->constraints[$type])) {
            $this->constraints[$type] = $this->extractConstraintsFromClass($type);
        }
        return $this->constraints[$type];
    }

    private function extractConstraintsFromClass(string $className): array
    {
        $reflection = $this->getReflectionClass($className);
        $constraints = [];
        foreach ($reflection->getProperties() as $property) {
            $propertyConstraints = [];
            if ($property->getAttributes()) {
                foreach ($property->getAttributes() as $attribute) {
                    $constraintClass = $attribute->getName();
                    if (is_subclass_of($constraintClass, Constraint::class, true)) {
                        $propertyConstraints[] = $attribute->newInstance();
                    }
                }
            }
            $propertyName = $property->getName();
            $constraints[$propertyName] = $propertyConstraints;
        }
        return $constraints;
    }

    private function getReflectionClass($className): ReflectionClass
    {
        if (!isset($this->reflectionClassMap[$className])) {
            $this->reflectionClassMap[$className] = new ReflectionClass($className);
        }
        return $this->reflectionClassMap[$className];
    }
}