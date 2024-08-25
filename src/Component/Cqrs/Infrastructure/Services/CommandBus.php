<?php

namespace Untek\Component\Cqrs\Infrastructure\Services;

use Psr\Container\ContainerInterface;
use Untek\Component\Cqrs\Application\Services\CommandBusConfiguratorInterface;
use Untek\Component\Cqrs\Application\Services\CommandBusInterface;

class CommandBus implements CommandBusInterface
{

    public function __construct(
        private ContainerInterface $container,
        private CommandBusConfiguratorInterface $commandConfigurator
    )
    {
    }

    public function handle(object $command): mixed
    {
        $handlerClass = $this->getHandlerByCommandClass($command);
        $handler = $this->container->get($handlerClass);
        return $handler($command);
    }

    public function getHandlerByCommandClass(object $command): string
    {
        $commandClass = get_class($command);
        return $this->commandConfigurator->getHandlerByCommandClass($commandClass);
    }
}