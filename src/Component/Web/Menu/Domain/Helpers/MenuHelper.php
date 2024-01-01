<?php

namespace Untek\Component\Web\Menu\Domain\Helpers;

use Untek\Component\I18Next\Facades\I18Next;
use Untek\Component\Web\Html\Helpers\Url;

class MenuHelper
{

    public static function filterByVisible(array $data): array
    {
        $result = [];
        foreach ($data as $index => $item) {
            if (self::isVisibleItem($item)) {
                $result[$index] = $item;
            }
        }
        return $result;
    }

    private static function isVisibleItem(array $item): bool
    {
        $isVisible = true;
        if (isset($item['visible'])) {
            if ($item['visible'] === false) {
                $isVisible = false;
            }
            unset($item['visible']);
        }
        return $isVisible;
    }

    public static function prepareModule(array $data): array
    {
        foreach ($data as &$item) {
            if (isset($item['module'])) {
//                $item['visible'] = isset(Yii::$app->modules[$item['module']]);
                unset($item['module']);
            }
        }
        return $data;
    }

    public static function prepareLabel(array $data): array
    {
        foreach ($data as &$item) {
            if (isset($item['label']) && is_array($item['label'])) {
                $item['label'] = I18Next::translateFromArray($item['label']);
            }
        }
        return $data;
    }

    public static function prepareItems(array $items): array
    {
        $result = [];
        foreach ($items as $index => $item) {
//            if (isset($item['module']) && isset(Yii::$app->modules[$item['module']])) {
            if (isset($item['module'])) {
                if (isset($item['url'])) {
                    $item['url'] = Url::to($item['url']);
                }
                $result[] = $item;
            }
        }
        return $result;
    }
}
