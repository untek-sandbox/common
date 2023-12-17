<?php

namespace Untek\Database\Seed\Application\Handlers;

use Doctrine\DBAL\Connection;
use Untek\Component\FormatAdapter\StoreFile;
use Untek\Core\FileSystem\Helpers\FileHelper;
use Untek\Core\FileSystem\Helpers\FilePathHelper;
use Untek\Database\Base\Domain\Libs\Dependency;
use Untek\Database\Seed\Application\Commands\ImportSeedCommand;
use Untek\Database\Seed\Application\Validators\ImportSeedCommandValidator;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

class ImportSeedCommandHandler
{

    public function __construct(
        private Dependency $dependency,
        private Connection $connection,
        private string $directory
    )
    {
    }

    /**
     * @param ImportSeedCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(ImportSeedCommand $command)
    {
        $validator = new ImportSeedCommandValidator();
        $validator->validate($command);

        $tables = $command->getTables();

        $seeds = FileHelper::findFiles($this->directory);
        $seedList = [];
        foreach ($seeds as $seedFile) {
            $seedName = FilePathHelper::fileNameOnly($seedFile);
            $seedList[$seedName] = $seedFile;
        }

        $sortedTables = $this->dependency->run($tables);

        foreach ($sortedTables as $seedName) {
            if(isset($seedList[$seedName])) {
                $seedFile = $seedList[$seedName];
                $this->import($seedName, $seedFile, $command->getProgressCallback());
            }
        }
        
        /*$this->connection->beginTransaction();
        try {
            $this->connection->query('SET FOREIGN_KEY_CHECKS=0');
            
            $this->connection->query('SET FOREIGN_KEY_CHECKS=1');
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollback();
        }*/
        
    }

    private function import(string $tableName, string $seedFile, $cb)
    {
        $store = new StoreFile($seedFile);
        $data = $store->load();

        $this->connection->query('DELETE FROM ' . $tableName);
        foreach ($data as $row) {
            $this->connection->insert($tableName, $row);
        }

        call_user_func($cb, $tableName);
    }
}