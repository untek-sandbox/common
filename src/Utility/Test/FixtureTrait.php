<?php

namespace Untek\Utility\Test;

use Untek\Database\Seed\Application\Commands\ImportSeedCommand;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;

trait FixtureTrait
{

    abstract protected function get(string $id): object;

    protected function loadFixtures()
    {
        $fixtures = $this->fixtures();
        if ($fixtures) {
            $importCommand = new ImportSeedCommand();
            $importCommand->setTables($fixtures);
            /** @var CommandBusInterface $bus */
            $bus = $this->get(CommandBusInterface::class);
            $bus->handle($importCommand);
        }
    }

    protected function fixtures(): array
    {
        return [];
    }
}
