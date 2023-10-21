<?php

use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateCliCommand;
use Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateApplication\GenerateApplicationFakeInteract;
use Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateRestApi\GenerateRestApiFakeInteract;
use Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateApplication\GenerateApplicationInteract;
use Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateRestApi\GenerateRestApiInteract;

return function (CommandConfiguratorInterface $commandConfigurator, \Psr\Container\ContainerInterface $container) {

    $isTest = true;
    $commandBus = $container->get(\Untek\Model\Cqrs\CommandBusInterface::class);
    $commandConfigurator->registerCommandInstance(new GenerateCliCommand(
        'code-generator:generate-application',
        $commandBus,
        [
            ($isTest ? new GenerateApplicationFakeInteract() : new GenerateApplicationInteract()),
        ]
    ));
    $commandConfigurator->registerCommandInstance(new GenerateCliCommand(
        'code-generator:generate-rest-api',
        $commandBus,
        [
            ($isTest ? new GenerateRestApiFakeInteract() : new GenerateRestApiInteract()),
        ]
    ));
};
