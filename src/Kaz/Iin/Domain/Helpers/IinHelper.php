<?php

namespace Untek\Kaz\Iin\Domain\Helpers;


use Untek\Core\Text\Helpers\TextHelper;
use Untek\Kaz\Iin\Domain\Entities\IndividualEntity;
use Untek\Kaz\Iin\Domain\Libs\CheckSum;

class IinHelper
{

    public static function extractNumber(string $iin): string
    {
        preg_match('/(\d+)/i', $iin, $matches);
        return $matches[1];
    }

    public static function entityToString(IndividualEntity $individualEntity): string
    {
        $iin =
            TextHelper::fill($individualEntity->getBirthday()->getDecade(), 2, '0', 'before') .
            TextHelper::fill($individualEntity->getBirthday()->getMonth(), 2, '0', 'before') .
            TextHelper::fill($individualEntity->getBirthday()->getDay(), 2, '0', 'before') .
            $individualEntity->getCentury() .
            TextHelper::fill($individualEntity->getSerialNumber(), 4, '0', 'before');
        $checkSum = new CheckSum();
        $checkSumEntity = $checkSum->generateSum($iin);
        $iin .= $checkSumEntity->getSum();
        return $iin;
    }
}
