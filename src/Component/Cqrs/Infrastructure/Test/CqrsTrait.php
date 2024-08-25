<?php

namespace Untek\Component\Cqrs\Infrastructure\Test;

use Untek\Component\Cqrs\Application\Services\CommandBusInterface;

trait CqrsTrait
{

    protected function handleCommand(object $command): mixed
    {
        /** @var CommandBusInterface $bus */
        $bus = static::getContainer()->get(CommandBusInterface::class);
        return $bus->handle($command);
    }
}
