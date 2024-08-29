<?php

namespace Untek\Model\Validator;

use ReflectionClass;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;
use Yiisoft\Arrays\ArrayHelper;

class ValidationConstraintExtractor
{

    private array $reflectionClassMap;
    private array $constraints = [];

    /**
     * @param object|string $type
     * @return Constraint[]
     */
    public function extract(object|string $type): array
    {
        if (is_object($type)) {
            $type = get_class($type);
        }
        if (!isset($this->constraints[$type])) {
            $reqiredConstraints = $this->extractRequiredConstraintsFromClass($type);
            $attributeConstraints = $this->extractConstraintsFromClass($type);
            $propertyConstraints = ArrayHelper::merge($reqiredConstraints, $attributeConstraints);
            foreach ($propertyConstraints as $field => &$constraints) {
                $hasRequired = false;
                foreach ($constraints as $constraint) {
                    if(get_class($constraint) == NotBlank::class) {
                        $hasRequired = true;
                    }
                }
                if(!$hasRequired) {
                    $constraints = new Optional($constraints);
                }
            }
            $this->constraints[$type] = $propertyConstraints;
        }
        return $this->constraints[$type];
    }

    public function extractRequiredConstraintsFromClass(string $className): array
    {
        $reflection = $this->getReflectionClass($className);
        $constraints = [];
        foreach ($reflection->getProperties() as $property) {
            $propertyConstraints = [];
            if ($property->getType()) {
                $isRequired = !$property->getType()->allowsNull() && !$property->hasDefaultValue();
                if($isRequired) {
                    $propertyConstraints[] = new NotBlank();
                }
                if($property->getType() instanceof \ReflectionUnionType) {
                    $unionConstraints = [];
                    foreach ($property->getType()->getTypes() as $typeName) {
                        $unionConstraints[] = new Type($typeName);
                    }
                    $propertyConstraints[] = new AtLeastOneOf([
                        'constraints' => $unionConstraints,
                    ]);
                } else {
                    $typeName = $property->getType()->getName();
                    if(!in_array($typeName, ['mixed'])) {
                        $propertyConstraints[] = new Type($typeName);
                    }
                }
            }
            if($propertyConstraints) {
                $propertyName = $property->getName();
                $constraints[$propertyName] = $propertyConstraints;
            }
        }
        return $constraints;
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
            if($propertyConstraints) {
                $propertyName = $property->getName();
                $constraints[$propertyName] = $propertyConstraints;
            }
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