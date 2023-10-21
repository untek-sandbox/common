<?php

use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateRestApiCliCommand;
use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateApplicationCliCommand;
use Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateApplicationInteract;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateCliCommand;
use Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateRestApiInteract;
use Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateRestApiFakeInteract;
use Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateApplicationFakeInteract;

return function (CommandConfiguratorInterface $commandConfigurator, \Psr\Container\ContainerInterface $container) {

    $commandBus = $container->get(\Untek\Model\Cqrs\CommandBusInterface::class);
    $commandConfigurator->registerCommandInstance(new GenerateCliCommand($commandBus,
        'code-generator:generate-application',
        [
//            new GenerateApplicationInteract(),
            new GenerateApplicationFakeInteract(),
        ]
    ));
    $commandConfigurator->registerCommandInstance(new GenerateCliCommand($commandBus,
        'code-generator:generate-rest-api',
        [
//            new GenerateRestApiInteract(),
            new GenerateRestApiFakeInteract(),
        ]
    ));


//    $commandConfigurator->registerCommandClass(GenerateApplicationCliCommand::class);
//    $commandConfigurator->registerCommandClass(GenerateRestApiCliCommand::class);
};
