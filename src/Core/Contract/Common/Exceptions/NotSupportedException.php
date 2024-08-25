<?php

namespace Untek\Core\Contract\Common\Exceptions;

use Exception;

/**
 * Функционал не поддерживается в данной реализации
 * 
 * Например, когда реализуем драйвер, 
 * и что-то этим драйвером не поддерживается, 
 * метод все равно реализуем, но внутри вызываем это исключение.
 */
class NotSupportedException extends Exception
{

}
