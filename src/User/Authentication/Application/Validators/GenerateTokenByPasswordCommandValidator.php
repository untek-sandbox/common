<?php

namespace Untek\User\Authentication\Application\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Untek\Model\Validator\Libs\AbstractObjectValidator;

class GenerateTokenByPasswordCommandValidator extends AbstractObjectValidator
{

    public function getConstraint(): Constraint
    {
        return new Collection([
            'fields' => [
                'login' => [
                    new NotBlank(),
                ],
                'password' => [
                    new NotBlank(),
                ],
            ]
        ]);
    }
}