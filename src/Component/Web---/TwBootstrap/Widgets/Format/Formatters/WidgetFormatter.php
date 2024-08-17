<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters;

use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Code\Helpers\PhpHelper;

class WidgetFormatter extends BaseFormatter implements FormatterInterface
{

    public $widget;

    public function render($value)
    {
        $widget = PhpHelper::runValue($this->widget, [$this->attributeEntity->getEntity()]);
        $widget = ClassHelper::createObject($widget);
        return $widget->run();
    }
}
