<?php

namespace Untek\Component\Measure\Byte\Enums;

use Untek\Core\Enum\Interfaces\GetLabelsInterface;

/**
 * Единицы измерения информации в байтах
 */
class ByteEnum implements GetLabelsInterface
{

    const BIT_PER_BYTE = 8;
    const STEP = 1024;

    const B = 1;
    const KB = self::STEP;
    const MB = self::KB * self::STEP;
    const GB = self::MB * self::STEP;
    const TB = self::GB * self::STEP;
    const PB = self::TB * self::STEP;
    const EB = self::PB * self::STEP;
//    const ZB = self::EB * self::STEP;
//    const YB = self::ZB * self::STEP;

    public static function allUnits()
    {
        return [
            'B' => 1,
            'KB' => self::KB,
            'MB' => self::MB,
            'GB' => self::GB,
            'TB' => self::TB,
            'PB' => self::PB,
            'EB' => self::EB,
//            'ZB' => self::ZB,
//            'YB' => self::YB,
        ];
    }

    public static function getLabels()
    {
        return [
            self::B => 'B',
            self::KB => 'KB',
            self::MB => 'MB',
            self::GB => 'GB',
            self::TB => 'TB',
            self::PB => 'PB',
            self::EB => 'EB',
//            self::ZB => 'ZB',
//            self::YB => 'YB',
        ];
    }
}
