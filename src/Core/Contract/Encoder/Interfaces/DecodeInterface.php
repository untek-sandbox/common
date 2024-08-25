<?php

namespace Untek\Core\Contract\Encoder\Interfaces;

/**
 * Возможность декодирования данных
 */
interface DecodeInterface
{

    /**
     * Декодирование данных
     * 
     * @param mixed $encodedData Закодированные данные
     * @return mixed
     */
    public function decode($encodedData);

}
