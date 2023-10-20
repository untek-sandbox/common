<?php

namespace Untek\Model\Pagination\Constrains;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;

class ExpandConstraintValidator extends AbstractConstraintValidator
{

    protected function getConstraint(Constraint $constraint): Constraint
    {
        /** @var ExpandConstraint $constraint */
        return new Choice([
            'choices' => $constraint->relations,
            'multiple' => true,
        ]);
    }
}
