<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Component\LogReader\Presentation\Http\Site\Controllers\GetLogByIdController;
use Untek\Component\LogReader\Presentation\Http\Site\Controllers\LogListController;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire();

    $services->set(LogListController::class, LogListController::class)
        ->tag('http.controller', [
//            'name' => 'log-list',
            'path' => '/log',
//            'methods' => ['GET'],
        ]);

    $services->set(GetLogByIdController::class, GetLogByIdController::class)
        ->tag('http.controller', [
//            'name' => 'log-details',
            'path' => '/log/{date}/{id}',
//            'methods' => ['GET'],
        ]);
};