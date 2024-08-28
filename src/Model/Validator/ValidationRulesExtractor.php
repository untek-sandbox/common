<?php

namespace Untek\Model\Validator;

use Symfony\Component\Validator\Constraint;
use ReflectionClass;

class ValidationRulesExtractor
{

    private array $reflectionClassMap;
    private array $rules = [];

    public function extractRuels(object|string $type): array
    {
        if(is_object($type)) {
            $type = get_class($type);
        }
        if(!isset($this->rules[$type])) {
            $this->rules[$type] = $this->extractRulesFromClass($type);
        }
        return $this->rules[$type];
    }

    private function extractRulesFromClass(string $type): array
    {
        $reflection = $this->getReflectionClass($type);
        $rules = [];
        foreach ($reflection->getProperties() as $property) {
            if ($property->getAttributes()) {
                foreach ($property->getAttributes() as $attribute) {
                    $constraintClass = $attribute->getName();
                    if (is_subclass_of($constraintClass, Constraint::class, true)) {
                        $constraintInstance = $attribute->newInstance();
                        $rules[$property->getName()][] = $constraintInstance;
                    }
                }
            }
        }
        return $rules;
    }

    private function getReflectionClass($className): ReflectionClass
    {
        if (!isset($this->reflectionClassMap[$className])) {
            $this->reflectionClassMap[$className] = new ReflectionClass($className);
        }
        return $this->reflectionClassMap[$className];
    }
}