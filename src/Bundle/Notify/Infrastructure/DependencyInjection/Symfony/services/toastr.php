<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Untek\Bundle\Notify\Domain\Interfaces\Repositories\ToastrRepositoryInterface;
use Untek\Bundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use Untek\Bundle\Notify\Domain\Repositories\Symfony\ToastrRepository;
use Untek\Bundle\Notify\Domain\Services\ToastrService;
use Untek\Component\Web\HtmlRender\Application\Services\JsResourceInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\ToastrAsset;
use Untek\Component\Web\Widget\Widgets\Toastr\ToastrWidget;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(ToastrAsset::class, ToastrAsset::class);

    $services->set(ToastrRepositoryInterface::class, ToastrRepository::class)
        ->args(
            [
                service(EntityManagerInterface::class),
                service(SessionInterface::class),
            ]
        );

    $services->set(ToastrServiceInterface::class, ToastrService::class)
        ->args(
            [
                service(ToastrRepositoryInterface::class),
            ]
        );

    $services->set(ToastrWidget::class, ToastrWidget::class)
        ->public()
        ->args(
            [
                service(ToastrServiceInterface::class),
                service(JsResourceInterface::class),
            ]
        );
};