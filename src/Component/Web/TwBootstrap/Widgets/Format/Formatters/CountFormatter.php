<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters;

class CountFormatter extends BaseFormatter implements FormatterInterface
{

    public $yesLabel;
    public $noLabel;

    public function render($items)
    {
        return count($items);
    }
}
