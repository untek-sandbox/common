<?php

namespace Untek\Lib\Components\Byte\Helpers;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Component\Measure\Byte\Enums\ByteEnum;
use Untek\Core\Enum\Helpers\EnumHelper;

DeprecateHelper::hardThrow();

class ByteSizeFormatHelper
{

    public static function sizeUnit(int $sizeByte)
    {
        $units = ByteEnum::allUnits();
        foreach ($units as $name => $value) {
            if ($sizeByte / $value < ByteEnum::STEP) {
                return $value;
            }
        }
    }

    public static function sizeFormat(int $sizeByte, $precision = 2)
    {
        $unitKey = self::sizeUnit($sizeByte);
        $value = round($sizeByte / $unitKey, 2);
        $label = EnumHelper::getLabel(ByteEnum::class, $unitKey);
        return $value . ' ' . $label;
    }
}
