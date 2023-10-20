<?php

use Untek\Database\Migration\Presentation\Cli\Commands\DownCommand;
use Untek\Database\Migration\Presentation\Cli\Commands\UpCommand;
use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;

return function (CommandConfiguratorInterface $commandConfigurator) {
    $commandConfigurator->registerCommandClass(UpCommand::class);
    $commandConfigurator->registerCommandClass(DownCommand::class);
};
