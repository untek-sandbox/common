<?php

use Untek\Component\LogReader\Presentation\Http\Site\Controllers\GetLogByIdController;
use Untek\Component\LogReader\Presentation\Http\Site\Controllers\LogListController;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Component\App\Services\ControllerAccessChecker;
use Untek\Component\Web\TwBootstrap\Widgets\Breadcrumb\BreadcrumbWidget;
use Untek\Component\Web\View\Libs\View;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(LogListController::class, LogListController::class)
        ->args([
            service(View::class),
            service(BreadcrumbWidget::class),
            service(ControllerAccessChecker::class),
        ])
        ->tag('http.controller', [
            'name' => 'log-list',
            'path' => '/log',
            'methods' => ['GET'],
        ])
    ;

    $services->set(GetLogByIdController::class, GetLogByIdController::class)
        ->args([
            service(View::class),
            service(BreadcrumbWidget::class),
            service(ControllerAccessChecker::class),
        ])
        ->tag('http.controller', [
            'name' => 'log-details',
            'path' => '/log/{date}/{id}',
            'methods' => ['GET'],
        ])
    ;
};