<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Component\Web\TwBootstrap\Widgets\Breadcrumb\BreadcrumbWidget;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(BreadcrumbWidget::class, BreadcrumbWidget::class);
};