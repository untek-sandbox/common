<?php


namespace Untek\Utility\CodeGenerator\Presentation\Cli\Interacts;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Utility\CodeGenerator\Application\Commands\GenerateRestApiCommand;

class GenerateRestApiFakeInteract extends GenerateRestApiInteract
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

    private function getCommandsFromNameSpace(string $namespace): array
    {
        $commandDirectory = PackageHelper::pathByNamespace($namespace . '\\Application\\Commands');
        $queryDirectory = PackageHelper::pathByNamespace($namespace . '\\Application\\Queries');

        $commandClasses = $queueClasses = [];
        $fs = new Filesystem();

        if ($fs->exists($commandDirectory)) {
            $commandClasses = $this->getResourcesByPath($commandDirectory);
        }
        if ($fs->exists($queryDirectory)) {
            $queueClasses = $this->getResourcesByPath($queryDirectory);
        }

        $commandClasses = $this->getClassNames($commandClasses);
        $queueClasses = $this->getClassNames($queueClasses);

        $classes = array_merge($commandClasses, $queueClasses);
        return $classes;
    }
}