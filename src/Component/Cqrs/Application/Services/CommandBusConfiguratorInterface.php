<?php

namespace Untek\Component\Cqrs\Application\Services;

interface CommandBusConfiguratorInterface
{

    public function define(string $commandClass, string $handlerClass): void;
}