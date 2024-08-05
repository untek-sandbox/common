<?php

namespace Untek\Utility\Test;

use Doctrine\DBAL\Connection;
use Untek\Database\Seed\Application\Commands\ImportSeedCommand;
use Untek\Model\Cqrs\Application\Services\CommandBusInterface;

trait FixtureTrait
{

//    abstract protected function get(string $id): object;

    protected function loadFixtures()
    {
        $fixtures = $this->fixtures();
        if ($fixtures) {
            $importCommand = new ImportSeedCommand();
            $importCommand->setTables($fixtures);
            /** @var CommandBusInterface $bus */
            $bus = static::getContainer()->get(CommandBusInterface::class);
            /** @var Connection $connection */
            $connection = static::getContainer()->get(Connection::class);
            try {
                $bus->handle($importCommand);
                $connection->close();
            } catch (\Exception $e) {
                $connection->close();
                throw $e;
            }
        }
    }

    protected function fixtures(): array
    {
        return [];
    }
}
