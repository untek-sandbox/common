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
        $sortedTables = $this->dependency->run($tables);

        $seeds = FileHelper::findFiles($this->directory);
        $seedList = [];
        foreach ($seeds as $seedFile) {
            $seedName = FilePathHelper::fileNameOnly($seedFile);
            $seedList[$seedName] = $seedFile;
        }

        foreach ($seedList as $seedName => $seedFile) {
            $this->import($seedName, $seedFile);
        }
    }

    private function import(string $tableName, string $seedFile)
    {
        $store = new StoreFile($seedFile);
        $data = $store->load();
        dump($tableName . ' - ' . count($data));
    }
}