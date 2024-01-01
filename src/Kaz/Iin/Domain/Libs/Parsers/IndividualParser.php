<?php

namespace Untek\Kaz\Iin\Domain\Libs\Parsers;

use Untek\Kaz\Iin\Domain\Entities\BaseEntity;
use Untek\Kaz\Iin\Domain\Entities\IndividualEntity;
use Untek\Kaz\Iin\Domain\Enums\SexEnum;
use Untek\Kaz\Iin\Domain\Helpers\CenturyHelper;

class IndividualParser implements ParserInterface
{

    private $dateParser;

    public function __construct()
    {
        $this->dateParser = new IndividualDateParser();
    }

    public function parse(string $value): BaseEntity
    {
        $dateEntity = $this->dateParser->parse($value);

        $individualEntity = new IndividualEntity();
        $individualEntity->setValue($value);
        $individualEntity->setSex(CenturyHelper::getSexFromCentury(substr($value, 6, 1)));
        $individualEntity->setCentury(substr($value, 6, 1));
        $individualEntity->setBirthday($dateEntity);
        $individualEntity->setSerialNumber(substr($value, 7, 4));
        $individualEntity->setCheckSum(substr($value, 11, 1));
        return $individualEntity;
    }
}
