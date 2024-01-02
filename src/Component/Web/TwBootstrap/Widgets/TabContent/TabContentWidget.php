<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\TabContent;

use Untek\Core\Text\Helpers\Inflector;
use Untek\Component\Web\Widget\Base\BaseWidget2;

class TabContentWidget extends BaseWidget2
{

    public $contentClass;
    public $items = [];

    public function run(): string
    {
        $hasActive = false;
        foreach ($this->items as &$item) {
            $item['name'] = hash('crc32b', $item['title']);
            /*if (empty($item['title'])) {
                $name = preg_replace('/(\d+)/i', ' $1 ', $item['name']);
                $item['title'] = Inflector::titleize($name);
            }*/
            $item['is_active'] = $item['is_active'] ?? false;
        }
        if (!$hasActive) {
            $this->items[0]['is_active'] = true;
        }
        return $this->render('index', [
            'contentClass' => $this->contentClass,
            'items' => $this->items,
        ]);
    }
}