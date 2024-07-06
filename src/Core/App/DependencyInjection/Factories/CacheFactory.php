<?php

namespace Untek\Core\App\DependencyInjection\Factories;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CacheFactory
{

    public static function create()
    {
        return new FilesystemAdapter('app_cache', 3600);
    }
}