<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\ToastrRepositoryInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\ToastrServiceInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Infrastructure\Drivers\Symfony\ToastrRepository;
use Untek\Component\Web\Widget\Widgets\Toastr\Infrastructure\Services\ToastrService;
use Untek\Component\Web\HtmlRender\Application\Services\JsResourceInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Presentation\Http\Site\Widgets\ToastrAsset;
use Untek\Component\Web\Widget\Widgets\Toastr\Presentation\Http\Site\Widgets\ToastrWidget;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(ToastrAsset::class, ToastrAsset::class);

    $services->set(ToastrRepositoryInterface::class, ToastrRepository::class)
        ->args(
            [
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