<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Component\Web\TwBootstrap\Widgets\Alert\AlertWidget;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(AlertWidget::class, AlertWidget::class);
};