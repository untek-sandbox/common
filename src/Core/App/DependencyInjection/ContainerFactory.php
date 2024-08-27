<?php

namespace Untek\Core\App\DependencyInjection;

use Psr\Container\ContainerInterface;

class ContainerFactory
{

    private static ?ContainerInterface $container;

    public static function set(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    public static function get(): ContainerInterface
    {
        return self::$container;
    }
}
