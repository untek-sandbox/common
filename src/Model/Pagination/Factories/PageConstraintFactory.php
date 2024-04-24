<?php

namespace Untek\Model\Pagination\Factories;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Positive;

class PageConstraintFactory
{

    public static function getConstraint($max): Constraint
    {
        return new Collection([
            'fields' => [
                'number' => [
                    new Positive(),
                ],
                'size' => [
                    new Positive(),
                    new LessThanOrEqual($max)
                ],
            ]
        ], null, null, null, true);
    }
}
