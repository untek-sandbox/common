<?php

namespace Untek\Component\Relation\Constraints;

use Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Model\Repository\Interfaces\FindOneInterface;
use Untek\Model\Validator\Constraints\BaseValidator;

class RelationValidator extends BaseValidator
{

    protected $constraintClass = Relation::class;

    public function validate($value, Constraint $constraint)
    {
        /*if (!$constraint instanceof Relation) {
            throw new UnexpectedTypeException($constraint, Relation::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (empty($value)) {
            return;
        }*/

        $this->checkConstraintType($constraint);
        if (empty($value)) {
            return;
        }

        if (!is_numeric($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'int');
        }

        /** @var EntityManagerInterface $em */
        $em = ContainerHelper::getContainer()->get(EntityManagerInterface::class);
        /** @var FindOneInterface $repository */
        $repository = $em->getRepository($constraint->foreignEntityClass);

        try {
            $repository->findOneById($value);
        } catch (NotFoundException $e) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        } catch (Exception $e) {
            if ($constraint->message) {
                $message = $constraint->message;
            } else {
                $message = $e->getMessage();
            }
            $this->context->buildViolation($message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
