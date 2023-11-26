<?php

namespace Untek\Database\Seed\Application\Commands;

class ImportSeedCommand
{

    private array $tables;

    public function getTables(): array
    {
        return $this->tables;
    }

    public function setTables(array $tables): void
    {
        $this->tables = $tables;
    }
}