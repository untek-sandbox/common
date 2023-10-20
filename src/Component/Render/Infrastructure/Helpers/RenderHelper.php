<?php

namespace Untek\Component\Render\Infrastructure\Helpers;

use ReflectionClass;

/**
 * Class RenderHelper
 * @package Untek\Component\Generator\Infrastructure\Helpers
 * @todo kill old class
 * @see Untek\Component\Web\HtmlRender\Infrastructure\Helpers\RenderHelper
 */
class RenderHelper
{

    public static function getRenderDirectoryByClass(object $class, string $viewPath = 'views'): string
    {
        $reflector = new ReflectionClass($class);
        $classFile = $reflector->getFileName();
        $classDirectory = dirname($classFile);
        $viewsDirectory = $classDirectory . DIRECTORY_SEPARATOR . $viewPath;
        return $viewsDirectory;
    }

    public static function includeRender(string $__viewFile, array $__params = []): void
    {
        extract($__params);
        include $__viewFile;
    }
}
