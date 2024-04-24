<?php

namespace Untek\Database\Base\Domain\Helpers;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\DotEnv\Domain\Libs\DotEnv;
use Untek\Database\Base\Domain\Enums\DbDriverEnum;

class SqlHelper
{

    public static function generateRawTableName(string $tableName): string {
        $items = explode('.', $tableName);
        return '"' . implode('"."', $items) . '"';
    }

    public static function isHasSchemaInTableName(string $tableName): bool {
        return strpos($tableName, '.') !== false;
    }

    public static function extractSchemaFormTableName(string $tableName): string {
        $tableName = str_replace('"', '', $tableName);
        return explode('.', $tableName)[0];
    }

}