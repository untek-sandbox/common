<?php

namespace Untek\Component\Arr\Libs;

use Illuminate\Support\Arr;

/**
 * Преобразование древовидного массива в плоский.
 *
 * Ключ в плоском массиве составляется из ключей вложенных элементов, разделяя уровни точкой.
 */
class FlatArray
{

    /**
     * Преобразовать древовидный массив в плоский.
     *
     * @param array $array
     * @return array
     */
    public function encode(array $array): array
    {
        return $this->_encode($array);
    }

    /**
     * Преобразовать плоский массив в древовидный.
     *
     * @param array $array
     * @return array
     */
    function decode(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            Arr::set($result, $key, $value);
        }
        return $result;
    }

    private function _encode(array $array, string $prefixKey = null, &$result = []): array
    {
        foreach ($array as $key => $value) {
            if ($prefixKey) {
                $key = $prefixKey . '.' . $key;
            }
            if (is_array($value)) {
                $this->_encode($value, $key, $result);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
