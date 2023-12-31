<?php

use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Framework\Socket\Presentation\Cli\Commands\SocketCommand;
use Untek\Framework\Socket\Presentation\Cli\Commands\SendMessageToSocketCommand;

return function (CommandConfiguratorInterface $commandConfigurator) {
    $commandConfigurator->registerCommandClass(SocketCommand::class);
    $commandConfigurator->registerCommandClass(SendMessageToSocketCommand::class);
};
