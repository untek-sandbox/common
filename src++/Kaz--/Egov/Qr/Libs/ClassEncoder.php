<?php

namespace Untek\Kaz\Egov\Qr\Libs;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Collection\Libs\Collection;

class ClassEncoder
{

    private $assoc = [];

    public function __construct(array $assoc)
    {
        $this->assoc = $assoc;
    }

    private function encoderToClass(string $name)
    {
        return ArrayHelper::getValue($this->assoc, $name);
    }

    public function encodersToClasses(array $names): CollectionEncoder
    {
        $classes = [];
        foreach ($names as $name) {
            $classes[] = $this->encoderToClass($name);
        }
        $encoders = new CollectionEncoder(new Collection($classes));
        return $encoders;
    }

}