<?php

namespace Untek\Database\Eloquent\Domain\Libs\ConfigBuilders;

use Untek\Core\Code\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class EloquentConfigBuilder
{

    public static function build(array $connection) {
        return $connection;
    }
}
