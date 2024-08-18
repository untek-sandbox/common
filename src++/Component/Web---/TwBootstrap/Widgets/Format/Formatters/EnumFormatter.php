<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters;

use Untek\Core\Enum\Helpers\EnumHelper;

class EnumFormatter extends BaseFormatter implements FormatterInterface
{

    public $enumClass;

    public function render($value)
    {
        return EnumHelper::getLabel($this->enumClass, $value);
    }
}
