<?php

namespace Untek\Model\Pagination\Constrains;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

abstract class AbstractConstraintValidator extends ConstraintValidator
{

    abstract protected function getConstraint(Constraint $constraint): Constraint;

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Constraint) {
            throw new UnexpectedTypeException($constraint, Constraint::class);
        }

        /** @var ConstraintViolationList $violations */
        $violations = $this->context->getValidator()->validate($value, $this->getConstraint($constraint));

        if ($violations) {
            foreach ($violations as $violation) {
                $this->context->buildViolation($violation->getMessage(), $violation->getParameters())
                    ->atPath($violation->getPropertyPath())
                    ->addViolation();
            }
        }
    }
}
