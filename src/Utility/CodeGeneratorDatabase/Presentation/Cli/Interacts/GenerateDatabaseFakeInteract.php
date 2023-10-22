<?php


namespace Untek\Utility\CodeGeneratorDatabase\Presentation\Cli\Interacts;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Utility\CodeGeneratorDatabase\Application\Commands\GenerateDatabaseCommand;
use Untek\Utility\CodeGenerator\Application\Interfaces\InteractInterface;

class GenerateDatabaseFakeInteract extends GenerateDatabaseInteract implements InteractInterface
{

    public function input(SymfonyStyle $io): array
    {
        $namespace = 'Forecast\Map\Modules\Park';
        $tableName = 'park_car';
        $properties = [
            [
                'name' => 'id',
                'type' => 'int',
            ],
            [
                'name' => 'title',
                'type' => 'string',
            ],
            [
                'name' => 'user_id',
                'type' => 'int',
            ],

        ];

        $command = new GenerateDatabaseCommand();
        $command->setNamespace($namespace);
        $command->setTableName($tableName);
        $command->setProperties($properties);

        return [$command];
    }
}