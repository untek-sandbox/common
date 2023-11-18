<?php

use Psr\Container\ContainerInterface;
use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Model\Cqrs\CommandBusInterface;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateCodeCommand;

return function (CommandConfiguratorInterface $commandConfigurator, ContainerInterface $container) {
    $commandBus = $container->get(CommandBusInterface::class);
    $commandConfigurator->registerCommandInstance(new GenerateCodeCommand(
        'code-generator:generate',
        $commandBus,
        []
    ));
};
