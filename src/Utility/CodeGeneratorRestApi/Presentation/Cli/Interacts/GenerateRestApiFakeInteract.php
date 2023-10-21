<?php


namespace Untek\Utility\CodeGeneratorRestApi\Presentation\Cli\Interacts;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGenerator\Application\Interfaces\InteractInterface;

class GenerateRestApiFakeInteract extends GenerateRestApiInteract implements InteractInterface
{

    public function input(SymfonyStyle $io): array
    {
        $namespace = 'Forecast\Map\Modules\Park';
        $commandClasses = $this->getCommandsFromNameSpace($namespace);
        if ($commandClasses) {
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
        }
    }
}