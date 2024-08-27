<?php

namespace Untek\Component\Env\Helpers;

use Untek\Component\Env\Enums\OsFamilyEnum;

/**
 * Работа с семействами операционных систем
 */
class OsHelper
{

    /**
     * Проверяет, принадлежит ли текущая ОС к определенному семейству
     * 
     * Семейства операционных систем определены в OsFamilyEnum.
     * @param string $family
     * @return bool
     */
    public static function isFamily(string $family): bool
    {
        return self::osFamily() == $family;
    }

    /**
     * Получить имя семества текущей ОС
     * @return mixed|string
     */
    public static function osFamily()
    {
        if ('\\' === DIRECTORY_SEPARATOR) {
            return 'Windows';
        }

        $map = array(
            'Darwin' => OsFamilyEnum::DARWIN,
            'DragonFly' => OsFamilyEnum::BSD,
            'FreeBSD' => OsFamilyEnum::BSD,
            'NetBSD' => OsFamilyEnum::BSD,
            'OpenBSD' => OsFamilyEnum::BSD,
            'Linux' => OsFamilyEnum::LINUX,
            'SunOS' => OsFamilyEnum::SOLARIS,
        );

        return isset($map[PHP_OS]) ? $map[PHP_OS] : 'Unknown';
    }
}