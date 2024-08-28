<?php

namespace Untek\Model\Validator;

use Symfony\Component\Validator\Constraint;

class ValidationRulesExtractor
{

    private array $reflectionClassMap;

    public function extractRuels(object|string $type): array
    {
        $reflection = $this->getReflectionClass($type);
        $rules = [];
        foreach ($reflection->getProperties() as $property) {
            if ($property->getAttributes()) {
                foreach ($property->getAttributes() as $attribute) {
                    $constraintClass = $attribute->getName();
                    if (is_subclass_of($constraintClass, Constraint::class, true)) {
                        $constraintArguments = $attribute->getArguments();
                        $constraintInstance = new $constraintClass(...$constraintArguments);
                        $rules[$property->getName()][] = $constraintInstance;
                    }
                }
            }
        }
        return $rules;
    }

    private function getReflectionClass($className): \ReflectionClass
    {
        if (!isset($this->reflectionClassMap[$className])) {
            $this->reflectionClassMap[$className] = new \ReflectionClass($className);
        }
        return $this->reflectionClassMap[$className];
    }
}