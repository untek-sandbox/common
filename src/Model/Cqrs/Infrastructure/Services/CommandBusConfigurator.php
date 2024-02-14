<?php

namespace Untek\Model\Cqrs\Infrastructure\Services;

use RuntimeException;
use Untek\Model\Cqrs\Application\Services\CommandBusConfiguratorInterface;

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
            throw new RuntimeException('Not found handler for command "' . $commandClass . '".');
        }
        return $this->definitions[$commandClass];
    }
}