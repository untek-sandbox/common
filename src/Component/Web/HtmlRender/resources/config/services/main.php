<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Untek\Component\Web\HtmlRender\Application\Services\CssResourceInterface;
use Untek\Component\Web\HtmlRender\Application\Services\HtmlRenderInterface;
use Untek\Component\Web\HtmlRender\Application\Services\JsResourceInterface;
use Untek\Component\Web\HtmlRender\Infrastructure\Services\CssResource;
use Untek\Component\Web\HtmlRender\Infrastructure\Services\HtmlRender;
use Untek\Component\Web\HtmlRender\Infrastructure\Services\JsResource;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(HtmlRenderInterface::class, HtmlRender::class)
        ->args(
            [
                service(UrlGeneratorInterface::class),
                service(JsResourceInterface::class),
                service(CssResourceInterface::class),
            ]
        );

    $services->set(JsResourceInterface::class, JsResource::class);
    $services->set(CssResourceInterface::class, CssResource::class);
};