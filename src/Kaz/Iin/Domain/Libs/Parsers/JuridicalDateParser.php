<?php

namespace Untek\Kaz\Iin\Domain\Libs\Parsers;

use Untek\Kaz\Iin\Domain\Entities\DateEntity;
use Untek\Kaz\Iin\Domain\Exceptions\BadDateException;

class JuridicalDateParser implements DateParserInterface
{

    public function parse(string $value): DateEntity
    {
        $dateEntity = new DateEntity();
        $dateEntity->setDecade(substr($value, 0, 2));
        $dateEntity->setMonth(substr($value, 2, 2));
        $dateEntity->setDay( '01');

       $this->validateDate($dateEntity);
        return $dateEntity;
    }
    
    private function validateDate(DateEntity $dateEntity): void
    {
        $isValid = $dateEntity->getMonth() >= 1 && $dateEntity->getMonth() <= 12 && $dateEntity->getYear() >= 0 && $dateEntity->getYear() <= 99;
        if (!$isValid) {
            throw new BadDateException();
        }
    }
}
