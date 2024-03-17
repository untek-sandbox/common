<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services()->defaults()->public();

    $services->set(GenerateResultCollection::class, GenerateResultCollection::class);
};