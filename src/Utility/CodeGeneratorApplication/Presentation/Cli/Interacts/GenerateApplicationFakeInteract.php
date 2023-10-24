<?php


namespace Untek\Utility\CodeGeneratorApplication\Presentation\Cli\Interacts;

use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGenerator\Application\Interfaces\InteractInterface;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;

class GenerateApplicationFakeInteract implements InteractInterface
{

    public function input(SymfonyStyle $io): array
    {
        $namespace = 'Forecast\Map\Modules\Park';
        $properties = [
            [
                'name' => 'id',
                'type' => 'int',
            ],
        ];
        $entityName = 'CarPark';
        $crud = [
            'list' => [
                'type' => TypeEnum::QUERY,
                'name' => "Get{$entityName}List",
            ],
            'create' => [
                'type' => TypeEnum::COMMAND,
                'name' => "Create{$entityName}",
            ],
            'one' => [
                'type' => TypeEnum::QUERY,
                'name' => "Get{$entityName}ById",
            ],
            'update' => [
                'type' => TypeEnum::COMMAND,
                'name' => "Update{$entityName}ById",
            ],
            'delete' => [
                'type' => TypeEnum::COMMAND,
                'name' => "Delete{$entityName}ById",
            ],
        ];
        $commands = [];
        foreach ($crud as $item) {
            $type = $item['type'];
            $name = $item['name'];

            $command = new GenerateApplicationCommand();
            $command->setType($type);
            $command->setNamespace($namespace);
            $command->setName($name);
            $command->setProperties($properties);
            $commands[] = $command;
        }
        return $commands;
    }
}