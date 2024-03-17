<?php

use Untek\Model\Cqrs\Application\Services\CommandBusConfiguratorInterface;

//\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

return function (CommandBusConfiguratorInterface $configurator) {

    $configurator->define(\Untek\Database\Seed\Application\Commands\ImportSeedCommand::class, \Untek\Database\Seed\Application\Handlers\ImportSeedCommandHandler::class);

    $configurator->define(\Untek\Database\Seed\Application\Queries\GetTablesQuery::class, \Untek\Database\Seed\Application\Handlers\GetTablesQueryHandler::class);

    $configurator->define(\Untek\Database\Seed\Application\Commands\ExportSeedCommand::class, \Untek\Database\Seed\Application\Handlers\ExportSeedCommandHandler::class);
};