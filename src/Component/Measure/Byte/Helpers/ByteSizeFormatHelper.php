<?php

namespace Untek\Component\Measure\Byte\Helpers;

use Untek\Component\Measure\Byte\Enums\ByteEnum;
use Untek\Core\Enum\Helpers\EnumHelper;

class ByteSizeFormatHelper
{

    public static function sizeUnit(int $sizeByte, array $units)
    {
        foreach ($units as $name => $value) {
            if ($sizeByte / $value < ByteEnum::STEP) {
                return $value;
            }
        }
    }

    public static function sizeFormat(int $sizeByte, $precision = 2)
    {
        $units = ByteEnum::allUnits();
        $unitKey = self::sizeUnit($sizeByte, $units);
        $value = round($sizeByte / $unitKey, 2);
        $label = EnumHelper::getLabel(ByteEnum::class, $unitKey);
        return $value . ' ' . $label;
    }
}
