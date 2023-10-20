<?php

namespace Untek\Model\Cqrs;

use Psr\Container\ContainerInterface;

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