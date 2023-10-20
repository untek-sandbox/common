<?php

namespace Untek\Model\Cqrs;

interface CommandBusConfiguratorInterface
{

    public function define(string $commandClass, string $handlerClass): void;
}