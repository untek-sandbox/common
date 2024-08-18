<?php

namespace Untek\Component\Web\TwBootstrap\Widgets\Collection\Helpers;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Component\Web\Html\Helpers\Html;

class CollectionWidgetHelper
{
    
    public static function sortByField(string $label, string $fieldName, string $baseUrl, array $getParams = []): string
    {
        $sortParams = ArrayHelper::getValue($getParams, 'sort', []);
        $sortParams = self::normalizeOrderBy($sortParams);
        $direction = ArrayHelper::getValue($sortParams, $fieldName);
        if ($direction) {
            $arrow = $direction == SORT_ASC ? '<i class="fas fa-sort-alpha-down"></i>' : '<i class="fas fa-sort-alpha-up"></i>';
            $label .= ' ' . $arrow;
        }
        $invertDirection = $direction == SORT_ASC ? SORT_DESC : SORT_ASC;
        $sortParamsString = self::sortParamsToString([$fieldName => $invertDirection]);
        $getParams['sort'] = $sortParamsString;
        $uri = $baseUrl . '?' . http_build_query($getParams);
        return Html::a($label, $uri);
    }

    protected static function sortParamsToString(array $sortParams): string
    {
        $resultSortParams = [];
        foreach ($sortParams as $fieldName => $direction) {
            $itemPrefix = $direction == SORT_ASC ? '' : '-';
            $resultSortParams[] = $itemPrefix . $fieldName;
        }
        return implode(',', $resultSortParams);
    }

    /**
     * @param $columns
     * @return array
     */
    protected static function normalizeOrderBy($columns): array
    {
        if (is_array($columns)) {
            return $columns;
        }

        $columns = preg_split('/\s*,\s*/', trim($columns), -1, PREG_SPLIT_NO_EMPTY);
        $result = [];
        foreach ($columns as $column) {
            if ($column[0] == '-') {
                $column = trim($column, '-');
                $result[$column] = SORT_DESC;
            } else {
                $result[$column] = SORT_ASC;
            }
        }
        return $result;
    }
}
