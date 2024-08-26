<?php

namespace Untek\Component\Arr\Helpers;

use Closure;
use Yiisoft\Arrays\ArrayHelper;

/**
 * Хэлпер для работы с массивами.
 */
class ExtArrayHelper
{

    /**
     * Удалить пустые элементы массива.
     *
     * @param array $data
     * @return array
     */
    public static function removeEmptyItems(array $data): array
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * Получить элементы массива, содержащие указанные поля.
     *
     * @param array $array
     * @param array $keys
     * @return array
     */
    public static function extractItemsWithAttributes(array $array, array $keys = []): array
    {
        foreach ($array as &$item) {
            $item = self::extractByKeys($item, $keys);
        }
        return $array;
    }

    /**
     * Сортировка массива по длине строки.
     *
     * @param $a
     * @param $b
     * @return int
     */
    public static function sortByLen($a, $b): int
    {
        if (strlen($a) < strlen($b)) {
            return 1;
        } elseif (strlen($a) == strlen($b)) {
            return 0;
        } else {
            return -1;
        }
    }

    /**
     * Получить ключ первого элемента массива
     *
     * @param array $array
     * @return mixed
     */
    public static function firstKey(array $array): mixed
    {
        $keys = array_keys($array);
        $firstKey = $keys[0];
        return $firstKey;
    }

    public static function extractByKeys($array, $keys): mixed
    {
        if (empty($keys)) {
            return $array;
        }
        if (is_object($array)) {
            $array = ArrayHelper::toArray($array);
        }
        $result = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                $result[$key] = $array[$key];
            }
        }
        return $result;
    }

    public static function removeByValue($value, &$array): void
    {
        $key = array_search($value, $array);
        if ($key !== FALSE) {
            unset($array[$key]);
        }
    }

    public static function recursiveIterator(array $array, $callback): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::recursiveIterator($value, $callback);
            } else {
                $array[$key] = call_user_func($callback, $value);
            }
        }
        return $array;
    }

    public static function value(mixed $value): mixed
    {
        return $value instanceof Closure ? $value() : $value;
    }
}
