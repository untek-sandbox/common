<?php

namespace Untek\Utility\CodeGeneratorCli\Application\Handlers;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;
use Untek\Utility\CodeGeneratorCli\Application\Validators\GenerateCliCommandValidator;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Generators\CliCommandGenerator;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Generators\CliCommandShortcutGenerator;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Generators\ConsoleCommandConfigGenerator;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Generators\ContainerConfigGenerator;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

class GenerateCliCommandHandler
{

    /**
     * @param GenerateCliCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(GenerateCliCommand $command)
    {
        $validator = new GenerateCliCommandValidator();
        $validator->validate($command);

        $collection = new GenerateResultCollection();

        $resultCollection = (new CliCommandGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new ConsoleCommandConfigGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new ContainerConfigGenerator())->generate($command);
        $collection->merge($resultCollection);

        $resultCollection = (new CliCommandShortcutGenerator())->generate($command);
        $collection->merge($resultCollection);

        $files = [];
        $fs = new Filesystem();
        foreach ($collection->getAll() as $result) {
            $fs->dumpFile($result->getFileName(), $result->getCode());
            $files[] = GeneratorFileHelper::fileNameTotoRelative(realpath($result->getFileName()));
        }

        return $files;
    }
}