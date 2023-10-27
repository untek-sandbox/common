<?php


namespace Untek\Utility\CodeGeneratorRestApi\Presentation\Cli\Interacts;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\Application\Interfaces\InteractInterface;

class GenerateRestApiFakeInteract extends GenerateRestApiInteract implements InteractInterface
{

    public function input(SymfonyStyle $io): array
    {
        DeprecateHelper::hardThrow();
        $namespace = 'Forecast\Map\Modules\Park';
        $commandClasses = $this->getCommandsFromNameSpace($namespace);
//        dd($commandClasses);

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
            if($item['type'] === TypeEnum::COMMAND) {
                $commandClass = 'Commands\\' . $item['name'] . 'Command';
            } elseif($item['type'] === TypeEnum::QUERY) {
                $commandClass = 'Queries\\' . $item['name'] . 'Query';
            }
            if(in_array($commandClass, $commandClasses)) {
                if(preg_match('/Get(\w+)ListQuery/i', $commandClass)) {
                    $uri = 'car-park';
                    $method = 'GET';
                } elseif(preg_match('/Create(\w+)Command/i', $commandClass)) {
                    $uri = 'car-park';
                    $method = 'POST';
                } elseif(preg_match('/Get(\w+)ByIdQuery/i', $commandClass)) {
                    $uri = 'car-park/{id}';
                    $method = 'GET';
                } elseif(preg_match('/Update(\w+)Command/i', $commandClass)) {
                    $uri = 'car-park/{id}';
                    $method = 'PATCH';
                } elseif(preg_match('/Delete(\w+)ByIdCommand/i', $commandClass)) {
                    $uri = 'car-park/{id}';
                    $method = 'DELETE';
                } else {
                    throw new \Exception('fff');
                }

                $commandClassName = $namespace . '\\Application\\' . $commandClass;

                $command = new GenerateRestApiCommand();
                $command->setNamespace($namespace);
                $command->setCommandClass($commandClassName);
                $command->setUri($uri);
                $command->setHttpMethod($method);

                $commands[] = $command;

//                dump($commandClass . ' - ' .$method . ' ' . $uri);

                /*dump([
                    $item,
                    $commandClass,
                    $method . ' ' . $uri,
                ]);*/

//                dd($commandClass);
//                $commandClassName = $namespace . '\\Application\\' . $commandClass;
//                $uri = 'park/get-summary-by-id';
//                $method = 'GET';
//
//                $command = new GenerateRestApiCommand();
//                $command->setNamespace($namespace);
//                $command->setCommandClass($commandClassName);
//                $command->setUri($uri);
//                $command->setHttpMethod($method);
            }

        }

        return $commands;

//        dd($commandClasses);



        /*if ($commandClasses) {
            $commandClass = 'Queries\GetSummaryByIdQuery';

            $commandClassName = $namespace . '\\Application\\' . $commandClass;
            $uri = 'park/get-summary-by-id';
            $method = 'GET';

            $command = new GenerateRestApiCommand();
            $command->setNamespace($namespace);
            $command->setCommandClass($commandClassName);
            $command->setUri($uri);
            $command->setHttpMethod($method);

            return [$command];
        } else {
            $io->warning('Not found commands and queries in namespace "' . $namespace . '". 
Please, run command "code-generator:generate-application" and retry this command. 
Or select new namespace with exist commands.');
            return [];
        }*/
    }
}