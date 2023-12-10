<?php

namespace Untek\Framework\Telegram\Infrastructure;

use Untek\Framework\Telegram\Domain\Services\RouteService;

class RouteServiceFactory
{

    public static function createService($telegramRoutes): RouteService
    {
        $routes = [];
        foreach ($telegramRoutes as $containerConfig) {
            $requiredConfig = require($containerConfig);
            $routes = array_merge($routes, $requiredConfig);
        }
        $routeService = new RouteService();
        $routeService->setDefinitions($routes);
        return $routeService;
    }
}
