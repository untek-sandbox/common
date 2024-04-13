<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters;

class TextFormatter extends BaseFormatter implements FormatterInterface
{

    public function render($items)
    {
        return htmlspecialchars($items);
    }
}
