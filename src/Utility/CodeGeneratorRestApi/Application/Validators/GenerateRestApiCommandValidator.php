<?php

namespace Untek\Utility\CodeGeneratorRestApi\Application\Validators;

use Symfony\Component\Validator\Constraint;
use Untek\Model\Validator\Libs\AbstractObjectValidator;
use Symfony\Component\Validator\Constraints as Assert;

class GenerateRestApiCommandValidator extends AbstractObjectValidator
{

    public function getConstraint(): Constraint
    {
        return new Assert\Collection([
            'fields' => [
                'namespace' => [
                    new Assert\NotBlank(),
                    new Assert\Length(null, 1, 255),
                    new Assert\Type('string'),
                ],
                'moduleName' => [
                    new Assert\NotBlank(),
                    new Assert\Length(null, 1, 255),
                    new Assert\Type('string'),
                ],
                'commandClass' => [
                    new Assert\NotBlank(),
                    new Assert\Length(null, 1, 255),
                    new Assert\Type('string'),
                ],
                'uri' => [
                    new Assert\NotBlank(),
                    new Assert\Length(null, 1, 255),
                    new Assert\Type('string'),
                ],
                'httpMethod' => [
                    new Assert\NotBlank(),
                    new Assert\Length(null, 1, 255),
                    new Assert\Type('string'),
                ],
                'version' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                ],
            ]
        ]);
    }
}