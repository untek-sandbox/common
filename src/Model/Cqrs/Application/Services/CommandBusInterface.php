<?php

namespace Untek\Model\Cqrs\Application\Services;

interface CommandBusInterface
{

    public function handle(object $command): mixed;
}