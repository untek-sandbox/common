<?php

use Forecast\Map\Generic\FileStorage\Application\Handlers\GetFileByIdQueryHandler;
use Forecast\Map\Generic\FileStorage\Application\Handlers\UploadFileCommandHandler;
use Forecast\Map\Generic\FileStorage\Application\Services\CategoryRepositoryInterface;
use Forecast\Map\Generic\FileStorage\Application\Services\FileRepositoryInterface;
use Forecast\Map\Generic\FileStorage\Infrastructure\Libs\UploadHandleRunner;
use Forecast\Map\Generic\FileStorage\Infrastructure\Persistence\Eloquent\Repository\FileRepository;
use Forecast\Map\Generic\FileStorage\Infrastructure\Persistence\Memory\CategoryRepository;
use Forecast\Map\Generic\FileStorage\Presentation\Http\RestApi\Controllers\UploadFileController;
use Illuminate\Database\Capsule\Manager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\DriverInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Untek\Core\App\Services\ControllerAccessChecker;
use Untek\Core\Instance\Libs\Resolvers\InstanceResolver;
use Untek\Component\Cqrs\Application\Services\CommandBusInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public()->autowire()->autoconfigure();

    $services
        ->load('Untek\Component\Cqs\\', __DIR__ . '/../../..')
        ->exclude([
            __DIR__ . '/../../../Application/**/*{Command.php,Query.php}',
            __DIR__ . '/../../../{resources,Domain/Model}',
            __DIR__ . '/../../../**/*{Event.php,Helper.php,Message.php,Task.php,Relation.php,Normalizer.php}',
            __DIR__ . '/../../../**/{Dto,Enums}',
        ]);
};
