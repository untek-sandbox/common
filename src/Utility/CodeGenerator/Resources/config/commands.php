<?php

use Psr\Container\ContainerInterface;
use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateCodeCommand;
use Untek\Utility\CodeGeneratorApplication\Presentation\Cli\Interacts\GenerateApplicationFakeInteract;
use Untek\Utility\CodeGeneratorApplication\Presentation\Cli\Interacts\GenerateApplicationInteract;
use Untek\Utility\CodeGeneratorRestApi\Presentation\Cli\Interacts\GenerateRestApiFakeInteract;
use Untek\Utility\CodeGeneratorRestApi\Presentation\Cli\Interacts\GenerateRestApiInteract;

return function (CommandConfiguratorInterface $commandConfigurator, ContainerInterface $container) {

    $isTest = true;
    $commandBus = $container->get(\Untek\Model\Cqrs\CommandBusInterface::class);
    $commandConfigurator->registerCommandInstance(new GenerateCodeCommand(
        'code-generator:generate-application',
        $commandBus,
        [
            ($isTest ? new GenerateApplicationFakeInteract() : new GenerateApplicationInteract()),
        ]
    ));
    $commandConfigurator->registerCommandInstance(new GenerateCodeCommand(
        'code-generator:generate-rest-api',
        $commandBus,
        [
            ($isTest ? new GenerateRestApiFakeInteract() : new GenerateRestApiInteract()),
        ]
    ));
};
