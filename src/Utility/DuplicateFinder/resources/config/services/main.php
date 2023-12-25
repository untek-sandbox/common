<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Utility\DuplicateFinder\Presentation\Cli\Commands\FindCommand;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(FindCommand::class, FindCommand::class);
};