<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Component\Web\Html\Helpers\Html;

class LinkFormatter extends BaseFormatter implements FormatterInterface
{

    public $enumClass;
    public $linkAttribute = 'id';
    public $linkParam = 'id';
    public $uri;

    public function render($value)
    {
        $entity = $this->attributeEntity->getEntity();
        if ($this->attributeEntity->getAttributeName()) {
            $title = PropertyHelper::getValue($entity, $this->attributeEntity->getAttributeName());
        } else {
            $title = $value;
        }
        $link = PropertyHelper::getValue($entity, $this->linkAttribute);
        $uri = ArrayHelper::toArray($this->uri);
        $uri[$this->linkParam] = $link;
        return Html::a($title, $uri);
    }
}
