<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters;

use Untek\Component\Web\TwBootstrap\Widgets\Format\Entities\AttributeEntity;

interface FormatterInterface
{

    public function render($items);

    public function setAttributeEntity(AttributeEntity $attributeEntity): void;
}
