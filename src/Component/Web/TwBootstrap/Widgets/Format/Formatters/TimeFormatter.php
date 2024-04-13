<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters;

use DateTime;

class TimeFormatter extends BaseFormatter implements FormatterInterface
{

    public $format = 'Y-m-d H:i:s';

    public function render($time)
    {
        /** @var DateTime $time */
        return $time->format($this->format);
    }
}
