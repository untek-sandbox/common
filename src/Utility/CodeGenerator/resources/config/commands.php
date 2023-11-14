<?php

use Psr\Container\ContainerInterface;
use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;

return function (CommandConfiguratorInterface $commandConfigurator, ContainerInterface $container) {
    $commandBus = $container->get(\Untek\Model\Cqrs\CommandBusInterface::class);
};
