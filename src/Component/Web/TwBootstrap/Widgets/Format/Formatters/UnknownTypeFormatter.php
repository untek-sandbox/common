<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters;

use Untek\Component\I18Next\Facades\I18Next;

class UnknownTypeFormatter extends BaseFormatter implements FormatterInterface
{

    public $label;

    public function render($items)
    {
        return $this->label ?? I18Next::t('core', 'main.unknown_type');
    }
}
