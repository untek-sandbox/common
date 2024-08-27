<?php

namespace Untek\Component\Env\Libs;

use Illuminate\Support\Arr;

class DotEnvMap
{

    private static $map;

    public static function get(string $path = null, $default = null)
    {
        if (!isset(self::$map)) {
            self::forgeMap();
        }
        return self::getValue($path, $default);
    }

    private static function getValue(string $path = null, $default = null)
    {
        return Arr::get(self::$map, $path, $default);
    }

    private static function forgeMap(): void
    {
        foreach ($_ENV as $name => $value) {
            $pureName = self::prepareName($name);
            Arr::set(self::$map, $pureName, $value);
        }
    }

    private static function prepareName(string $name): string
    {
        $name = strtolower($name);
        $name = str_replace('_', '.', $name);
        return $name;
    }
}
