<?php

namespace Untek\Kaz\Iin\Domain\Enums;

use Untek\Core\Enum\Interfaces\GetLabelsInterface;

class TypeEnum implements GetLabelsInterface
{

    const INDIVIDUAL = 'individual';
    const JURIDICAL = 'juridical';
    
    public static function getLabels()
    {
        return [
            self::INDIVIDUAL => 'Физическое лицо',
            self::JURIDICAL => 'Юридическое лицо',
        ];
    }
}
