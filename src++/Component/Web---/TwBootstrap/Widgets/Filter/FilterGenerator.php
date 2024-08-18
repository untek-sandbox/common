<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Filter;

use Untek\Component\Web\TwBootstrap\Widgets\Filter\Widgets\Number\NumberFilterWidget;
use Untek\Component\Web\TwBootstrap\Widgets\Filter\Widgets\Select\SelectFilterWidget;
use Untek\Component\Web\TwBootstrap\Widgets\Filter\Widgets\Text\TextFilterWidget;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Arr\Helpers\ArrayHelper;

class FilterGenerator
{

    public static function generateByDefinition($definition, string $name, $value): string
    {
        $definition = ClassHelper::normalizeComponentConfig($definition);
        $widgetInstance = ClassHelper::createObject($definition['class']);
        $widgetDefaultAttributes = ArrayHelper::toArray($widgetInstance);
        $definition = ArrayHelper::merge($definition, $widgetDefaultAttributes);
        unset($definition['class']);
        //$definition['options']['onkeydown'] = 'filterForm.submitOnKeyDown(this, event)';
        $definition['name'] = $name;
        $definition['value'] = $value;
        ClassHelper::configure($widgetInstance, $definition);
        return $widgetInstance->run();
    }

    public static function generateByType(array $filterDefinition, string $name, $value, array $options = []): string
    {
        $widgetClass = self::getWidgetClassByType($filterDefinition['type']);
        unset($filterDefinition['type']);
        //$options['onkeydown'] = 'filterForm.submitOnKeyDown(this, event)';
        $definition = ClassHelper::normalizeComponentConfig($widgetClass);
        $definition = ArrayHelper::merge($definition, [
            'options' => $options,
            'name' => $name,
            'value' => $value,
        ]);
        $definition = ArrayHelper::merge($definition, $filterDefinition);
        if($definition['options']) {
            unset($definition['options']);
        }
        $widgetInstance = ClassHelper::createObject($definition);
        return $widgetInstance->run();
    }

    private static function getWidgetClassByType(string $type)
    {
        $widgetAssoc = self::widgetAssoc();
        return ArrayHelper::getValue($widgetAssoc, $type);
    }

    private static function widgetAssoc()
    {
        return [
            'text' => [
                'class' => TextFilterWidget::class,
                'options' => [
                    'class' => 'form-control',
                    //'onkeydown' => 'filterForm.submitOnKeyDown(this, event)',
                ],
            ],
            'number' => [
                'class' => NumberFilterWidget::class,
                'options' => [
                    'class' => 'form-control',
                    //'onkeydown' => 'filterForm.submitOnKeyDown(this, event)',
                ],
            ],
            'choice' => [
                'class' => SelectFilterWidget::class,
                'options' => [
                    'class' => 'form-control'
                ],
            ],
        ];
    }
}