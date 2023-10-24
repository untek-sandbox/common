<?php

use Untek\Model\Cqrs\CommandBusConfiguratorInterface;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Handlers\GenerateApplicationCommandHandler;
use Untek\Utility\CodeGeneratorRestApi\Application\Handlers\GenerateRestApiCommandHandler;

return function (CommandBusConfiguratorInterface $configurator) {
//    $configurator->define(GenerateApplicationCommand::class, GenerateApplicationCommandHandler::class);
//    $configurator->define(GenerateRestApiCommand::class, GenerateRestApiCommandHandler::class);
};
