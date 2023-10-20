<?php

namespace Untek\Model\Pagination\Constrains;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;

class SortConstraintValidator extends AbstractConstraintValidator
{

    protected function getConstraint(Constraint $constraint): Constraint
    {
        /** @var SortConstraint $constraint */
        $fields = [];
        foreach ($constraint->fields as $fieldName) {
            $fields[$fieldName] = new Choice([
                'choices' => [
                    SORT_ASC,
                    SORT_DESC,
                ],
            ]);
        }
        return new Collection([
            'fields' => $fields,
        ], null, null, null, true);
    }
}
