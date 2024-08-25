<?php

namespace Untek\Core\Contract\Common\Exceptions;

use Exception;
use Throwable;

/**
 * Заглушка для ненужных методов интерфейса
 * 
 * Например, реализуем CRUD-интерфейс репозитория, 
 * но метод delete нам не нужен, мы реализуем сам метод, 
 * а внутри ввызываем это исключение.
 */
class NotImplementedMethodException extends Exception
{

    /*public function __construct(string $className = null, string $methodName = null)
    {
        'Not Implemented Method "' . $className . '::' . $methodName . '"!';
    }*/
}
