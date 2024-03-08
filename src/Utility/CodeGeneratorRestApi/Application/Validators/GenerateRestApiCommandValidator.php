<?php

namespace Untek\Utility\CodeGeneratorRestApi\Application\Validators;

use Symfony\Component\Validator\Constraint;
use Untek\Model\Components\Constraints\Enum;
use Untek\Model\Validator\Libs\AbstractObjectValidator;
use Symfony\Component\Validator\Constraints as Assert;
use Untek\Utility\CodeGenerator\Application\Enums\CrudTypeEnum;

class GenerateRestApiCommandValidator extends AbstractObjectValidator
{

    public function getConstraint(): Constraint
    {
        return new Assert\Collection([
            'fields' => [
                'modelName' => [
                    new Assert\Length(null, 1, 255),
                    new Assert\Type('string'),
                ],
                'templates' => new Assert\Optional([
                    new Assert\Type('array'),
                ]),
                'properties' => new Assert\Optional([
                    new Assert\Type('array'),
                ]),
                'crudType' => new Assert\Optional([
                    new Enum(['class' => CrudTypeEnum::class]),
                ]),
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