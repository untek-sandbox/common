<?php

namespace Untek\Model\Cqrs;

use RuntimeException;

class CommandBusConfigurator implements CommandBusConfiguratorInterface
{

    private array $definitions = [];

    public function define(string $commandClass, string $handlerClass): void
    {
        $this->definitions[$commandClass] = $handlerClass;
    }

    public function getHandlerByCommandClass(string $commandClass): string
    {
        if (!isset($this->definitions[$commandClass])) {
            throw new RuntimeException('Not found handler for command!');
        }
        return $this->definitions[$commandClass];
    }
}