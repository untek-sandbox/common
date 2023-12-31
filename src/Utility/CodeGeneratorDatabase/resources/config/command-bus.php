<?php

use Untek\Model\Cqrs\Application\Services\CommandBusConfiguratorInterface;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGeneratorDatabase\Application\Handlers\GenerateDatabaseCommandHandler;

return function (CommandBusConfiguratorInterface $configurator) {
    $configurator->define(GenerateDatabaseCommand::class, GenerateDatabaseCommandHandler::class);
};
