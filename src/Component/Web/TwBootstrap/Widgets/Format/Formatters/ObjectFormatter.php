<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters;

use Untek\Model\Entity\Helpers\EntityHelper;

class ObjectFormatter extends BaseFormatter implements FormatterInterface
{

    public function render($object)
    {
        $array = EntityHelper::toArray($object);
        $arrayFormatter = new ArrayFormatter();
        return $arrayFormatter->render($array);
    }
}
