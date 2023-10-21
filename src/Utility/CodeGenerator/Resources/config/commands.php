<?php

use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateCliCommand;
use Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateApplication\GenerateApplicationFakeInteract;
use Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateRestApi\GenerateRestApiFakeInteract;

return function (CommandConfiguratorInterface $commandConfigurator, \Psr\Container\ContainerInterface $container) {

    $commandBus = $container->get(\Untek\Model\Cqrs\CommandBusInterface::class);
    $commandConfigurator->registerCommandInstance(new GenerateCliCommand($commandBus,
        'code-generator:generate-application',
        [
            new \Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateApplication\GenerateApplicationInteract(),
//            new GenerateApplicationFakeInteract(),
        ]
    ));
    $commandConfigurator->registerCommandInstance(new GenerateCliCommand($commandBus,
        'code-generator:generate-rest-api',
        [
            new \Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateRestApi\GenerateRestApiInteract(),
//            new GenerateRestApiFakeInteract(),
        ]
    ));
};
