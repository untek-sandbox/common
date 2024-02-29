<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var PropertyGenerator[] $properties
 */

use Untek\Utility\CodeGeneratorApplication\Presentation\Enums\PropertyTypeEnum;
use Laminas\Code\Generator\PropertyGenerator;

?>

namespace <?= $namespace ?>;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Untek\Model\Pagination\Constrains\ExpandConstraint;
use Untek\Model\Validator\Libs\AbstractObjectValidator;

class <?= $className ?> extends AbstractObjectValidator
{

    public function getConstraint(): Constraint
    {
        return new Collection([
            'fields' => [
                'id' => [
                    new NotBlank(),
                    new Positive(),
                ],
                'expand' => new ExpandConstraint([]),
            ]
        ]);
    }
}