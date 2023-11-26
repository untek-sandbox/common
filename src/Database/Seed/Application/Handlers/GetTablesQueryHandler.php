<?php

namespace Untek\Database\Seed\Application\Handlers;

use Doctrine\DBAL\Connection;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

class GetTablesQueryHandler
{

    public function __construct(private Connection $connection, private array $excludeTables)
    {
    }

    /**
     * @param \Untek\Database\Seed\Application\Queries\GetTablesQuery $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(\Untek\Database\Seed\Application\Queries\GetTablesQuery $command)
    {
        $validator = new \Untek\Database\Seed\Application\Validators\GetTablesQueryValidator();
        $validator->validate($command);

        $tableNames = $this->connection->getSchemaManager()->listTableNames();
        $tableNames = $this->fileterTables($tableNames);
        
        return $tableNames;
    }

    private function fileterTables(array $tableNames): array {
        $filteredTables = [];
        foreach ($tableNames as $tableName) {
            if(!in_array($tableName, $this->excludeTables)) {
                $filteredTables[] = $tableName;
            }
        }
        return $filteredTables;
    }
}