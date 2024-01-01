<?php

namespace Untek\Kaz\Iin\Domain\Libs;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Model\Validator\Exceptions\UnprocessibleEntityException;
use Untek\Model\Validator\Helpers\ValidationHelper;
use Untek\Kaz\Iin\Domain\Exceptions\BadCheckSumException;
use Untek\Kaz\Iin\Domain\Exceptions\CheckSumException;

class Validator
{

    public function validate(?string $value): void
    {
        $this->validateValue($value);
        $sequence = $this->validateCheckSum($value);
    }

    private function validateValue(?string $value): void
    {
        $violationList = ValidationHelper::validateValue($value, [
            new NotBlank(),
            new Length(['value' => 12]),
            new Regex(['pattern' => '/^\d+$/'])
        ]);
        if ($violationList->count()) {
            UnprocessableEntityException::throwException($violationList[0]->getMessage(), '[value]');

//            $unprocessibleEntityException = new UnprocessableEntityException();
//            $unprocessibleEntityException->setViolations($violationList);
            
            /*foreach ($violationList as $ValidationErrorEntity) {
                //$ValidationErrorEntity->setField('value');
            }
            $unprocessibleEntityException->setErrorCollection(ValidationHelper::createErrorCollectionFromViolationList($violationList));*/
//            throw $unprocessibleEntityException;
        }
    }

    private function validateCheckSum(string $value): array
    {
        $sumActual = intval(substr($value, 11, 1));
        $checkSum = new CheckSum();
        $checkSumEntity = $checkSum->generateSum($value);
        $sumCalculated = $checkSumEntity->getSum();
        if ($sumActual != $sumCalculated) {
            $message = 'Bad check sum! Actual: ' . $sumActual . ', expected: ' . $sumCalculated;
            throw new BadCheckSumException($message);
        }
        return $checkSumEntity->getSequence();
    }
}
