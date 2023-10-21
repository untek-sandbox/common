<?php

use Psr\Container\ContainerInterface;
use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateCodeCommand;
use Untek\Utility\CodeGeneratorRestApi\Presentation\Cli\Interacts\GenerateRestApiFakeInteract;
use Untek\Utility\CodeGeneratorRestApi\Presentation\Cli\Interacts\GenerateRestApiInteract;

return function (CommandConfiguratorInterface $commandConfigurator, ContainerInterface $container) {

    $isTest = true;
    $commandBus = $container->get(\Untek\Model\Cqrs\CommandBusInterface::class);
    $commandConfigurator->registerCommandInstance(new GenerateCodeCommand(
        'code-generator:generate-rest-api',
        $commandBus,
        [
            ($isTest ? new GenerateRestApiFakeInteract() : new GenerateRestApiInteract()),
        ]
    ));
};
