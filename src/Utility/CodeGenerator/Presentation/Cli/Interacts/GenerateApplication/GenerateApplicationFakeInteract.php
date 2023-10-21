<?php


namespace Untek\Utility\CodeGenerator\Presentation\Cli\Interacts\GenerateApplication;

use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Utility\CodeGenerator\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGenerator\Application\Interfaces\InteractInterface;

class GenerateApplicationFakeInteract implements InteractInterface
{

    public function input(SymfonyStyle $io): array
    {
        $namespace = 'Forecast\Map\Modules\Park';
        $type = 'query';
        $name = 'GetSummaryById';
        $properties = [
            [
                'name' => 'id',
                'type' => 'int',
            ],
        ];

        $command = new GenerateApplicationCommand();
        $command->setType($type);
        $command->setNamespace($namespace);
        $command->setName($name);
        $command->setProperties($properties);

        return [$command];
    }
}