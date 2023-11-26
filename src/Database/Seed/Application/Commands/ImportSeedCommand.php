<?php

namespace Untek\Database\Seed\Application\Commands;

class ImportSeedCommand
{

    private array $tables;
    private $progressCallback;

    public function getTables(): array
    {
        return $this->tables;
    }

    public function setTables(array $tables): void
    {
        $this->tables = $tables;
    }

    public function getProgressCallback()
    {
        return $this->progressCallback;
    }

    public function setProgressCallback($progressCallback): void
    {
        $this->progressCallback = $progressCallback;
    }
}