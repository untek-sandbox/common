<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters;

use Untek\Component\Web\TwBootstrap\Widgets\Format\Entities\AttributeEntity;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Libs\FormatEncoder;

abstract class BaseFormatter
{

    /** @var AttributeEntity */
    protected $attributeEntity;

    private $formatEncoder;

    public function setAttributeEntity(AttributeEntity $attributeEntity): void
    {
        $this->attributeEntity = $attributeEntity;
    }

    public function setFormatEncoder(FormatEncoder $formatEncoder): void
    {
        $this->formatEncoder = $formatEncoder;
    }

    public function getFormatEncoder(): FormatEncoder
    {
        return $this->formatEncoder;
    }

}
