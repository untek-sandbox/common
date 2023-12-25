<?php

use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Utility\DuplicateFinder\Presentation\Cli\Commands\FindCommand;

return function (CommandConfiguratorInterface $commandConfigurator) {
    $commandConfigurator->registerCommandClass(FindCommand::class);
};
