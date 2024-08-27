<?php

namespace Untek\Model\Validator\Exceptions;

use Exception;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UnprocessableEntityException extends Exception
{

    private ConstraintViolationListInterface $violations;

    /**
     * @return ConstraintViolationListInterface|ConstraintViolation[]
     */
    public function getViolations(): ConstraintViolationListInterface|array
    {
        return $this->violations;
    }

    public function setViolations(ConstraintViolationListInterface $violations): self
    {
        $this->violations = $violations;
        return $this;
    }

    public static function throwException($message, ?string $propertyPath, ?string $messageTemplate = null, array $parameters = [], $root = null, $invalidValue = null, int $plural = null, string $code = null): void
    {
        $unprocessable = new UnprocessableEntityException();
        $unprocessable->setViolations(new ConstraintViolationList([
            new ConstraintViolation($message, $messageTemplate, $parameters, $root, $propertyPath, $invalidValue),
        ]));
        throw $unprocessable;
    }
}
