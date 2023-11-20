<?php

namespace Untek\Model\Cqrs\Application\Services;

interface CommandBusConfiguratorInterface
{

    public function define(string $commandClass, string $handlerClass): void;
}