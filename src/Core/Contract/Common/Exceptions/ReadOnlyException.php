<?php

namespace Untek\Core\Contract\Common\Exceptions;

use Exception;

/**
 * Значение только для чтения
 * 
 * Обычно это исключение вызывается при попытке записи значения, предназначенного только для чтения.
 */
class ReadOnlyException extends Exception
{

}
