<?php

namespace Untek\Component\Measure\Time\Helper;

use Untek\Component\Measure\Time\Enums\TimeEnum;
use Untek\Core\Text\Helpers\TextHelper;
use Untek\Core\Text\Libs\TemplateRender;

class TimeFormatHelper
{

    public static function format(int $inputSeconds, string $mask): string
    {
        $units = [
            'y' => TimeEnum::SECOND_PER_YEAR,
            'd' => TimeEnum::SECOND_PER_DAY,
            'h' => TimeEnum::SECOND_PER_HOUR,
            'm' => TimeEnum::SECOND_PER_MINUTE,
            'i' => TimeEnum::SECOND_PER_SECOND,
        ];

        $result = self::calculate($units, $inputSeconds);
        $render = new TemplateRender('{', '}');
        $render->addReplacementList($result);
        return $render->renderTemplate($mask);
    }

    private static function calculate($units, $inputSeconds): array
    {
        $result = [];
        foreach ($units as $placeholder => $rate) {
            $placeholderUpper = mb_strtoupper($placeholder);
            if ($inputSeconds >= $rate) {
                $value = intval($inputSeconds / $rate);
                $inputSeconds = $inputSeconds % $rate;
            } else {
                $value = 0;
            }
            $result[$placeholder] = $value;
            $result[$placeholderUpper] = TextHelper::fill($value, 2, '0', 'before');
        }
        return $result;
    }
}
