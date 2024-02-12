<?php

use Untek\Model\Cqrs\Application\Services\CommandBusConfiguratorInterface;

return function (CommandBusConfiguratorInterface $configurator) {

    $configurator->define(\Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand::class, \Untek\Utility\CodeGeneratorCli\Application\Handlers\GenerateCliCommandHandler::class);
};
