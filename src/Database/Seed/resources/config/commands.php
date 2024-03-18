<?php

use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Database\Seed\Presentation\Cli\Commands\ImportSeedCliCommand;
use Untek\Database\Seed\Presentation\Cli\Commands\ExportSeedCliCommand;

\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();
return function (CommandConfiguratorInterface $commandConfigurator) {
    $commandConfigurator->registerCommandClass(ImportSeedCliCommand::class);
    $commandConfigurator->registerCommandClass(ExportSeedCliCommand::class);
};
