<?php

namespace Untek\Component\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use RuntimeException;

abstract class AbstractEntityCollection extends ArrayCollection implements EntityCollectionInterface
{

    public function __construct(array $elements = [])
    {
        foreach ($elements as $element) {
            $this->checkType($element);
        }
        parent::__construct($elements);
    }

    public function set($key, $value)
    {
        $this->checkType($value);
        parent::set($key, $value);
    }

    public function add($element)
    {
        $this->checkType($element);
        return parent::add($element);
    }

    private function checkType(object $element): void
    {
        $type = static::getClass();
        if (!$element instanceof $type) {
            throw new RuntimeException(sprintf("Element %s not instance of %s", $element, $type));
        }
    }
}