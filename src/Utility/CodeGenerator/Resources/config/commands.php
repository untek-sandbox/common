<?php

use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateRestApiCliCommand;
use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateApplicationCliCommand;

return function (CommandConfiguratorInterface $commandConfigurator) {
    $commandConfigurator->registerCommandClass(GenerateApplicationCliCommand::class);
    $commandConfigurator->registerCommandClass(GenerateRestApiCliCommand::class);
};
