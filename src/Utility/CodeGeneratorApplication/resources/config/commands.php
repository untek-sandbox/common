<?php

use Psr\Container\ContainerInterface;
use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateCodeCommand;
use Untek\Utility\CodeGeneratorApplication\Presentation\Cli\Interacts\GenerateApplicationInteract;

return function (CommandConfiguratorInterface $commandConfigurator, ContainerInterface $container) {
    $commandBus = $container->get(\Untek\Model\Cqrs\Application\Services\CommandBusInterface::class);
    $commandConfigurator->registerCommandInstance(new GenerateCodeCommand(
        'code-generator:generate-application',
        $commandBus,
        [
            new GenerateApplicationInteract(),
        ]
    ));
};
