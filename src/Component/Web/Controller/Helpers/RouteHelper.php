<?php

namespace Untek\Component\Web\Controller\Helpers;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Instance\Helpers\ClassHelper;


DeprecateHelper::hardThrow();

class RouteHelper
{

    public static function generateCrud(RoutingConfigurator $routes, string $controllerClass, string $basePath)
    {
        $controllerClassName = ClassHelper::getClassOfClassName($controllerClass);
        $baseRoute = trim($basePath, '/');
        $routes
            ->add($baseRoute . '/index', $basePath)
            ->controller([$controllerClass, 'index'])
            ->methods(['GET', 'POST']);
        $routes
            ->add($baseRoute . '/view', $basePath . '/view')
            ->controller([$controllerClass, 'view'])
            ->methods(['GET', 'POST']);
        $routes
            ->add($baseRoute . '/update', $basePath . '/update')
            ->controller([$controllerClass, 'update'])
            ->methods(['GET', 'POST']);
        $routes
            ->add($baseRoute . '/delete', $basePath . '/delete')
            ->controller([$controllerClass, 'delete'])
            ->methods(['GET', 'POST']);
        $routes
            ->add($baseRoute . '/create', $basePath . '/create')
            ->controller([$controllerClass, 'create'])
            ->methods(['GET', 'POST']);
    }
}
