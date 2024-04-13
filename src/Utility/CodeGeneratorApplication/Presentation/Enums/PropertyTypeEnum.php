<?php

namespace Untek\Utility\CodeGeneratorApplication\Presentation\Enums;

use DateTimeImmutable;

class PropertyTypeEnum
{

    const INTEGER = 'int';
    const FLOAT = 'float';
    const STRING = 'string';
    const ARRAY = 'array';
    const BOOL = 'bool';
    const OBJECT = 'object';
    const DATE_TIME = DateTimeImmutable::class;

    public static function getList(): array
    {
        return [
            self::INTEGER,
            self::FLOAT,
            self::STRING,
            self::ARRAY,
            self::BOOL,
            self::OBJECT,
            self::DATE_TIME,
        ];
    }
}