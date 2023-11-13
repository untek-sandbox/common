<?php

use Psr\Container\ContainerInterface;
use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;
use Untek\Model\Cqrs\CommandBusInterface;
use Untek\Utility\GoogleTranslate\Presentation\Cli\Commands\TranslateJsonCommand;
use Untek\Utility\GoogleTranslate\Presentation\Cli\Commands\ExtractValuesFromJsonCommand;

return function (CommandConfiguratorInterface $commandConfigurator, ContainerInterface $container) {
    $commandBus = $container->get(CommandBusInterface::class);
    $commandConfigurator->registerCommandInstance(new TranslateJsonCommand(
        'google:translate',
        $commandBus,
        []
    ));
    $commandConfigurator->registerCommandInstance(new ExtractValuesFromJsonCommand(
        'google:extract-values-from-json',
        $commandBus,
        []
    ));
};
