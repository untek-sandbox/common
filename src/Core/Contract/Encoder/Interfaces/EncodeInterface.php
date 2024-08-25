<?php

namespace Untek\Core\Contract\Encoder\Interfaces;

/**
 * Возможность кодирования данных
 */
interface EncodeInterface
{

    /**
     * Кодирование данных
     *
     * @param mixed $data Исходные данные
     * @return mixed
     */
    public function encode($data);

}
