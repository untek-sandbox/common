<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Libs;

use DateTime;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Entities\AttributeEntity;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Enums\TypeEnum;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\ArrayFormatter;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\BooleanFormatter;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\DoubleFormatter;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\EnumFormatter;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\FormatterInterface;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\HtmlFormatter;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\IntegerFormatter;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\NullFormatter;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\ObjectFormatter;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\ResourceClosedFormatter;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\ResourceFormatter;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\StringFormatter;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\TimeFormatter;
use Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters\UnknownTypeFormatter;

class FormatEncoder
{

    private $formatterClasses = [];

    public function getFormatterClasses(): array
    {
        return $this->formatterClasses;
    }

    public function setFormatterClasses(array $formatterClasses): void
    {
        $this->formatterClasses = ArrayHelper::merge($this->getDefaultFormatterClasses(), $formatterClasses);
    }

    public function encode(AttributeEntity $attributeEntity): string
    {
        $value = $attributeEntity->getValue();
        /*if($value == null) {
            return '--';
        }*/
        $formatterInstance = $this->getFormatterInstance($attributeEntity);
        $formatterInstance->setAttributeEntity($attributeEntity);
        $formatterInstance->setFormatEncoder($this);
        return $formatterInstance->render($value);
    }

    public function encodeValue($value): string
    {

    }

    private function getFormatterInstance(AttributeEntity $attributeEntity): FormatterInterface
    {
        $formatterClass = $this->getFormatterClass($attributeEntity);
        return ClassHelper::createObject($formatterClass);
    }

    private function getFormatterClass(AttributeEntity $attributeEntity)//: string
    {
        $formatterClasses = $this->getFormatterClasses();
        $value = $attributeEntity->getValue();
        $valueType = gettype($value);
        if ($valueType == TypeEnum::NULL) {
            return $formatterClasses[TypeEnum::NULL];
        }
        if ($attributeEntity->getFormatter()) {
            return $attributeEntity->getFormatter();
        }
        $format = $attributeEntity->getFormat();
        if ($format) {
            return ArrayHelper::getValue($formatterClasses, $format, TypeEnum::STRING);
        }
        if ($valueType == TypeEnum::OBJECT) {
            $valueClass = get_class($value);
            if (isset($formatterClasses[$valueClass])) {
                return $formatterClasses[$valueClass];
            }
        }
        if (isset($formatterClasses[$valueType])) {
            return $formatterClasses[$valueType];
        }
        return ArrayHelper::getValue($formatterClasses, TypeEnum::STRING);
    }

    private function getDefaultFormatterClasses(): array
    {
        return [
            'html' => HtmlFormatter::class,
            'enum' => EnumFormatter::class,

            TypeEnum::BOOLEAN => BooleanFormatter::class,
            TypeEnum::INTEGER => IntegerFormatter::class,
            TypeEnum::DOUBLE => DoubleFormatter::class,
            TypeEnum::STRING => StringFormatter::class,
            TypeEnum::ARRAY => ArrayFormatter::class,
            TypeEnum::OBJECT => ObjectFormatter::class,
            TypeEnum::RESOURCE => ResourceFormatter::class,
            TypeEnum::RESOURCE_CLOSED => ResourceClosedFormatter::class,
            TypeEnum::NULL => NullFormatter::class,
            TypeEnum::UNKNOWN_TYPE => UnknownTypeFormatter::class,

            DateTime::class => TimeFormatter::class,
        ];
    }
}
