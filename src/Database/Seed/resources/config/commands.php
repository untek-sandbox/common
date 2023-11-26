<?php

use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Database\Seed\Presentation\Cli\Commands\ImportSeedCliCommand;

return function (CommandConfiguratorInterface $commandConfigurator) {
    $commandConfigurator->registerCommandClass(ImportSeedCliCommand::class);
};
