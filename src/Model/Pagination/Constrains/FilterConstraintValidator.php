<?php

namespace Untek\Model\Pagination\Constrains;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;

class FilterConstraintValidator extends AbstractConstraintValidator
{

    protected function getConstraint(Constraint $constraint): Constraint
    {
        /** @var FilterConstraint $constraint */
        return new Collection(['fields' => $constraint->fields], null, null, null, true);
    }
}
