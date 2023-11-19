<?php

use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Database\Doctrine\Domain\Facades\DoctrineFacade;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(Connection::class, Connection::class)
        ->factory([DoctrineFacade::class, 'createConnection']);
};