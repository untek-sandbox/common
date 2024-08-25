<?php

namespace Untek\Component\Cqrs\Infrastructure\Services;

use RuntimeException;
use Untek\Component\Cqrs\Application\Services\CommandBusConfiguratorInterface;

class CommandBusConfigurator implements CommandBusConfiguratorInterface
{

    private array $definitions = [];

    public function define(string $commandClass, string $handlerClass): void
    {
        if(array_key_exists($commandClass, $this->definitions)) {
            throw new \Exception(sprintf('Command "%s" already defined!'.PHP_EOL.'Defined handler "%s", '.PHP_EOL.'current handler "%s".', $commandClass, $this->definitions[$commandClass], $handlerClass));
        }
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