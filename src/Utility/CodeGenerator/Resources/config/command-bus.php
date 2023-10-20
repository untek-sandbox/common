<?php

use Untek\Model\Cqrs\CommandBusConfiguratorInterface;
use Untek\Utility\CodeGenerator\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGenerator\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\Application\Handlers\GenerateApplicationCommandHandler;
use Untek\Utility\CodeGenerator\Application\Handlers\GenerateRestApiCommandHandler;

return function (CommandBusConfiguratorInterface $configurator) {
    $configurator->define(GenerateApplicationCommand::class, GenerateApplicationCommandHandler::class);
    $configurator->define(GenerateRestApiCommand::class, GenerateRestApiCommandHandler::class);
};
