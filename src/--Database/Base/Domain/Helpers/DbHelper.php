<?php

namespace Untek\Database\Base\Domain\Helpers;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\DotEnv\Domain\Libs\DotEnv;
use Untek\Database\Base\Domain\Enums\DbDriverEnum;

class DbHelper
{

    public static function encodeDirection($direction)
    {
        $directions = [
            SORT_ASC => 'asc',
            SORT_DESC => 'desc',
        ];
        return $directions[$direction];
    }

}