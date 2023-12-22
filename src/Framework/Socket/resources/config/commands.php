<?php

use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Framework\Socket\Symfony4\Commands\SocketCommand;
use Untek\Framework\Socket\Symfony4\Commands\SocketIoCommand;

return function (CommandConfiguratorInterface $commandConfigurator) {
    $commandConfigurator->registerCommandClass(SocketCommand::class);
//    $commandConfigurator->registerCommandClass(SocketIoCommand::class);
};
