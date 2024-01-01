<?php

use Untek\FrameworkPlugin\HttpLayout\Infrastructure\Enums\HttpLayoutContainerParameterEnum;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\FrameworkPlugin\HttpLayout\Infrastructure\Subscribers\SetLayoutSubscriber;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(SetLayoutSubscriber::class, SetLayoutSubscriber::class)
        ->args(
            [
                service(HtmlRenderInterface::class)
            ]
        )
        ->call(
            'setLayout',
            [
                param(HttpLayoutContainerParameterEnum::TEMPLATE_LAYOUT_FILE)
            ]
        );
};