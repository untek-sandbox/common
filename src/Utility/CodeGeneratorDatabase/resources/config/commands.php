<?php

use Psr\Container\ContainerInterface;
use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Utility\CodeGenerator\Presentation\Cli\Commands\GenerateCodeCommand;
use Untek\Utility\CodeGeneratorDatabase\Presentation\Cli\Interacts\GenerateDatabaseInteract;

return function (CommandConfiguratorInterface $commandConfigurator, ContainerInterface $container) {

//    $isTest = true;
    $commandBus = $container->get(\Untek\Model\Cqrs\Application\Services\CommandBusInterface::class);
    $commandConfigurator->registerCommandInstance(new GenerateCodeCommand(
        'code-generator:generate-database',
        $commandBus,
        [
            new GenerateDatabaseInteract(),
        ]
    ));
};
