<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Entities;

use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Core\Code\Helpers\TypeHelper;

class AttributeEntity
{

    private $entity;
    private $collection;
    private $label;
    private $labelTranslate;
    private $attributeName;
    private $sortAttribute;
    private $sort;
    private $filter;
    private $value;
    private $format;
    private $formatter;

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity): void
    {
        $this->entity = $entity;
    }

    public function getCollection()
    {
        return $this->collection;
    }

    public function setCollection($collection): void
    {
        $this->collection = $collection;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label): void
    {
        $this->label = $label;
    }

    public function getLabelTranslate()
    {
        return $this->labelTranslate;
    }

    public function setLabelTranslate($labelTranslate): void
    {
        $this->labelTranslate = $labelTranslate;
    }

    public function getAttributeName()
    {
        return $this->attributeName;
    }

    public function setAttributeName($attributeName): void
    {
        $this->attributeName = $attributeName;
    }

    public function getSortAttribute()
    {
        if (empty($this->sortAttribute) && !empty($this->sort)) {
            return $this->attributeName;
        }
        return $this->sortAttribute;
    }

    public function setSortAttribute($sortAttribute): void
    {
        $this->sortAttribute = $sortAttribute;
    }

    public function getSort()
    {
        return $this->sort;
    }

    public function setSort($sort): void
    {
        $this->sort = $sort;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function setFilter($filter): void
    {
        $this->filter = $filter;
    }

    public function getValue()
    {
        $value = null;
        if (is_null($this->value)) {
            if ($this->attributeName === null) {
                $value = $this->entity;
            } elseif (empty($this->attributeName)) {
                $value = '';
            } else {
                try {
                    $value = PropertyHelper::getValue($this->entity, $this->attributeName);
                } catch (UnexpectedTypeException $e) {
                    $value = null;
                }
            }
        } elseif (TypeHelper::isCallable($this->value)) {
            $value = call_user_func($this->value, $this->entity);
        } else {
            $value = $this->value;
        }
        return $value;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function setFormat($format): void
    {
        $this->format = $format;
    }

    public function getFormatter()
    {
        return $this->formatter;
    }

    public function setFormatter($formatter): void
    {
        $this->formatter = $formatter;
    }
}
