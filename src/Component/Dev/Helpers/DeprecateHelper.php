<?php

namespace Untek\Component\Dev\Helpers;

use Untek\Component\Dev\Exceptions\DeprecatedException;

/**
 * Работа с устаревшим кодом
 */
class DeprecateHelper
{

    /**
     * Строгое устаревание
     *
     * Всегда вызывает исключение.
     * @param string $message
     * @throws DeprecatedException
     */
    public static function hardThrow(string $message = ''): void
    {
        $lastTraceItem = debug_backtrace()[0];
        if (empty($message)) {
            $message = $lastTraceItem['file'] . ':' . $lastTraceItem['line'];
        }
        throw new DeprecatedException('Deprecated: ' . $message);
    }
}