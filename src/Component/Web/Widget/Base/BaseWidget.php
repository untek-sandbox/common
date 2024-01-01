<?php

namespace Untek\Component\Web\Widget\Base;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Instance\Helpers\ClassHelper;

use Untek\Core\Text\Helpers\TemplateHelper;
use Untek\Component\Web\Widget\Interfaces\WidgetInterface;

//DeprecateHelper::hardThrow();

abstract class BaseWidget implements WidgetInterface
{

    abstract public function render(): string;

    public static function widget(array $options = []): string
    {
        $instance = ClassHelper::createObject(static::class, $options);
        return $instance->render();
    }

    protected function renderTemplate(string $templateCode, array $params)
    {
        return TemplateHelper::render($templateCode, $params);
    }
}