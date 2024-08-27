<?php

namespace Untek\Develop\Debug;

use axy\backtrace\helpers\Represent;
use axy\backtrace\Trace;

class DebugBacktrace
{

    public static function dump(int $limit = null, ?string $basePath = null): void
    {
        $items = debug_backtrace();
        array_shift($items);
        $trace = new Trace($items);
        if ($limit) {
            $trace->truncateByLimit($limit);
        }
        $basePath = $basePath ? $basePath : realpath(__DIR__ . '/../../../../../sf-blank');
        $trace->trimFilename($basePath);

        dump(Represent::trace($trace->items));
    }
}
