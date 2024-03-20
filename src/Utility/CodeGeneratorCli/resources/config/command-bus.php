<?php

use Untek\Model\Cqrs\Application\Services\CommandBusConfiguratorInterface;

\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

return function (CommandBusConfiguratorInterface $configurator) {

    $configurator->define(\Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand::class, \Untek\Utility\CodeGeneratorCli\Application\Handlers\GenerateCliCommandHandler::class);
};
