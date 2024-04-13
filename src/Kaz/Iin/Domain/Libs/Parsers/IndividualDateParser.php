<?php

namespace Untek\Kaz\Iin\Domain\Libs\Parsers;

use Untek\Kaz\Iin\Domain\Entities\DateEntity;
use Untek\Kaz\Iin\Domain\Exceptions\BadDateException;
use Untek\Kaz\Iin\Domain\Helpers\CenturyHelper;

class IndividualDateParser implements DateParserInterface
{

    public function parse(string $value): DateEntity
    {
        $smallYear = substr($value, 0, 2);
        $dateEntity = new DateEntity();

        $century = substr($value, 6, 1);
        $epoch = CenturyHelper::getEpochFromCentury($century) * 100;
        $dateEntity->setDecade($smallYear);
        $dateEntity->setMonth(substr($value, 2, 2));
        $dateEntity->setDay(substr($value, 4, 2));
        $dateEntity->setEpoch($epoch);

        $this->validateDate($dateEntity);
        return $dateEntity;
    }
    
    private function validateDate(DateEntity $dateEntity): void
    {
        $isValid = checkdate($dateEntity->getMonth(), $dateEntity->getDay(), $dateEntity->getYear());
        if (!$isValid) {
            throw new BadDateException();
        }
    }
}
