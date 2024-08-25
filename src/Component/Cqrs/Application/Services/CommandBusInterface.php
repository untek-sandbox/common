<?php

namespace Untek\Component\Cqrs\Application\Services;

interface CommandBusInterface
{

    public function handle(object $command): mixed;
}