<?php


namespace Untek\Utility\CodeGeneratorRestApi\Presentation\Cli\Interacts;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Framework\Console\Symfony4\Question\ChoiceQuestion;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Application\Helpers\CommandHelper;
use Untek\Utility\CodeGenerator\Application\Interfaces\InteractInterface;
use Untek\Utility\CodeGenerator\Presentation\Libs\Validator;

class GenerateRestApiInteract implements InteractInterface
{

    public function input(SymfonyStyle $io): array
    {
        $namespace = $io->ask('Enter a namespace', null, [Validator::class, 'validateClassName']);
        $commandClasses = $this->getCommandsFromNameSpace($namespace);
        if ($commandClasses) {
            $commandClass = $this->inputCommand($io, $commandClasses);

            $commandClassName = $namespace . '\\Application\\' . $commandClass;
            $uri = $io->ask('Enter a URI (for example: "user/{id}")', null, function ($value): ?string {
                Validator::notBlank($value);
                Validator::isEnglish($value);
                return $value;
            });

            $method = $this->inputHttpMethod($io, $commandClass);

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

    private function inputHttpMethod(SymfonyStyle $io, string $commandClass): string
    {
        $endCommandClassName = CommandHelper::getType($commandClass);

        $question = new ChoiceQuestion(
            'Select HTTP method',
            [
                'GET',
                'POST',
                'PATCH',
                'PUT',
                'DELETE',
            ],
            mb_strtolower($endCommandClassName) == 'query' ? 'GET' : null,
        );
        return $io->askQuestion($question);
    }

    protected function getCommandsFromNameSpace(string $namespace): array
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

    protected function getClassNames(array $classes): array
    {
        foreach ($classes as &$class) {
            $className = ClassHelper::getClassOfClassName($class);
            $namespace = ClassHelper::getNamespace($class);
            $oneLevelNamespace = ClassHelper::getClassOfClassName($namespace);
            $class = $oneLevelNamespace . '\\' . $className;
        }
        return $classes;
    }

    protected function getResourcesByPath(string $path): array
    {
        $finder = new Finder();
        $finder->files()->in($path)->name('*.php')->sortByName(true);
        $classes = [];
        foreach ($finder as $file) {
            $fileContent = file_get_contents($file->getRealPath());
            preg_match('/namespace (.+);/', $fileContent, $matches);
            $namespace = $matches[1] ?? null;
            if (!preg_match('/class +([^{ ]+)/', $fileContent, $matches)) {
                // no class found
                continue;
            }
            $className = trim($matches[1]);
            if (null !== $namespace) {
                $classes[] = $namespace . '\\' . $className;
            } else {
                $classes[] = $className;
            }
        }
        return $classes;
    }

    private function inputCommand(SymfonyStyle $io, array $commands): string
    {
        $question = new ChoiceQuestion(
            'Select command',
            $commands
        );
        return $io->askQuestion($question);
    }
}