<?php

namespace Untek\Component\Cqs\Infrastructure\Test;

use Untek\Component\Cqs\Application\Interfaces\CommandBusInterface;

trait CqsTrait
{

    protected function handleCommand(object $command): mixed
    {
        /** @var CommandBusInterface $bus */
        $bus = static::getContainer()->get(CommandBusInterface::class);
        return $bus->handle($command);
    }
}
