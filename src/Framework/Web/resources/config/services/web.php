<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Untek\Component\Web\Controller\Services\ControllerView;
use Untek\Component\Web\Form\Libs\FormManager;
use Untek\Lib\Web\View\Libs\View;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();
    $parameters = $configurator->parameters();

    $services->set(FormManager::class, FormManager::class)
        ->args(
            [
                service(FormFactoryInterface::class),
                service(CsrfTokenManagerInterface::class),
            ]
        );

    $services->set(View::class, View::class)
        ->args(
            [
                service(UrlGeneratorInterface::class),
                service(TranslatorInterface::class),
            ]
        );

    $services->set(ControllerView::class, ControllerView::class)
        ->args([
            service(UrlGeneratorInterface::class),
        ]);
};