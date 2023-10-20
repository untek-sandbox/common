<?php

namespace Untek\Model\Cqrs;

interface CommandBusInterface
{

    public function handle(object $command): mixed;
}