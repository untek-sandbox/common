<?php

namespace Untek\Model\Pagination\Constrains;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Positive;

class PageConstraintValidator extends AbstractConstraintValidator
{

    protected function getConstraint(Constraint $constraint): Constraint
    {
        return new Collection([
            'fields' => [
                'number' => [
                    new Positive(),
                ],
                'size' => [
                    new Positive(),
                    new LessThanOrEqual($constraint->max)
                ],
            ]
        ], null, null, null, true);
    }
}
