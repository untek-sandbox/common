<?php

namespace Untek\Kaz\Iin\Domain\Libs\Parsers;

use Untek\Kaz\Iin\Domain\Entities\BaseEntity;
use Untek\Kaz\Iin\Domain\Entities\JuridicalEntity;

class JuridicalParser implements ParserInterface
{

    private $dateParser;

    public function __construct()
    {
        $this->dateParser = new JuridicalDateParser();
    }

    public function parse(string $value): BaseEntity
    {
        $dateEntity = $this->dateParser->parse($value);

        $juridicalEntity = new JuridicalEntity();
        $juridicalEntity->setValue($value);
        $juridicalEntity->setType($value[4]);
        $juridicalEntity->setPart($value[5]);
        $juridicalEntity->setRegistrationDate($dateEntity);

        $juridicalEntity->setSerialNumber(substr($value, 6, 5));
        $juridicalEntity->setCheckSum(substr($value, 11, 1));
        return $juridicalEntity;
    }
}
