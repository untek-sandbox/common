<?php

namespace Untek\Tool\Dev\Trace\Facades;

use axy\backtrace\helpers\Represent;
use axy\backtrace\Trace;

class DebugBacktrace
{

    public static function dump(int $limit = null) {
        $items = debug_backtrace();
        array_shift($items);
        $trace = new Trace($items);
        if($limit) {
            $trace->truncateByLimit($limit);
        }
        $trace->trimFilename(getenv('ROOT_DIRECTORY'));
        
        dump(Represent::trace($trace->items));
    }
}
