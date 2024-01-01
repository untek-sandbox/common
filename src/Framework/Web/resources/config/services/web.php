<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Untek\Component\Web\Controller\Services\ControllerView;
use Untek\Component\Web\Form\Libs\FormManager;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $parameters = $configurator->parameters();

    $services->set(FormManager::class, FormManager::class)
        ->args(
            [
                service(FormFactoryInterface::class),
                service(CsrfTokenManagerInterface::class),
            ]
        );

    $services->set(ControllerView::class, ControllerView::class)
        ->args([
            service(UrlGeneratorInterface::class),
        ]);
};