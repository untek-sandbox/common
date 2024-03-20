<?php

use Untek\Model\Cqrs\Application\Services\CommandBusConfiguratorInterface;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Application\Handlers\GenerateRestApiCommandHandler;

\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

return function (CommandBusConfiguratorInterface $configurator) {
    $configurator->define(GenerateRestApiCommand::class, GenerateRestApiCommandHandler::class);
};
