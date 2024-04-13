<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Format\Formatters;


use Untek\Core\Text\Helpers\TemplateHelper;
use Untek\Core\Arr\Helpers\ArrayHelper;

class ArrayFormatter extends BaseFormatter implements FormatterInterface
{

    public $indexedItemWrapper = '<span class="badge badge-primary">{value}</span>';
    public $indexedSplitter = ' ';
    public $assocWrapper = '<ul>{items}</ul>';
    public $assocItemWrapper = '<li>{key}: {value}</li>';

    public function render($items)
    {
        if (ArrayHelper::isIndexed($items)) {
            return $this->formatIndexed($items);
        } else {
            return $this->formatAssoc($items);
        }
    }

    private function formatIndexed(array $items): string
    {
        $arr = [];
        foreach ($items as $key => $val) {
            $arr[] = TemplateHelper::render($this->indexedItemWrapper, ['value' => $val]);
        }
        return implode($this->indexedSplitter, $arr);
    }

    private function formatAssoc(array $items): string
    {
        $arr = [];
        foreach ($items as $key => $val) {
            $arr[] = TemplateHelper::render($this->assocItemWrapper, [
                'key' => $key,
                'value' => $val,
            ]);
        }
        $itemsHtml = implode(' ', $arr);
        return TemplateHelper::render($this->assocWrapper, ['items' => $itemsHtml]);
    }
}
