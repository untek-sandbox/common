<?php

namespace Untek\Database\Seed\Application\Validators;

use Symfony\Component\Validator\Constraint;
use Untek\Model\Validator\Libs\AbstractObjectValidator;
use Symfony\Component\Validator\Constraints as Assert;

class GetTablesQueryValidator extends AbstractObjectValidator
{

    public function getConstraint(): Constraint
    {
        return new Assert\Collection([

        ]);
    }
}