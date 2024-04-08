<?php

use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;

return function (CommandConfiguratorInterface $commandConfigurator) {
    $commandConfigurator->registerCommandClass(\Untek\Framework\Telegram\Symfony4\Commands\LongPullCommand::class);
};
