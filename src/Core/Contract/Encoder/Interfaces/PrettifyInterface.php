<?php

namespace Untek\Core\Contract\Encoder\Interfaces;

/**
 * Возможность форматирования данных, удобных для чтения человеком
 */
interface PrettifyInterface
{

    /**
     * Форматировать данные
     * @param $data
     * @return mixed
     */
    public function prettify($data);

}
