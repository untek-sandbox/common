<?php

namespace Untek\Utility\CodeGeneratorCli\Application\Handlers;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;
use Untek\Utility\CodeGeneratorCli\Application\Validators\GenerateCliCommandValidator;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Generators\CliCommandGenerator;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Generators\CliCommandShortcutGenerator;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Generators\ConsoleCommandConfigGenerator;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Generators\ContainerConfigGenerator;

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

        $generators = [
            new CliCommandGenerator(),
            new ConsoleCommandConfigGenerator(),
            new ContainerConfigGenerator(),
            new CliCommandShortcutGenerator(),
        ];

        foreach ($generators as $generator) {
            $resultCollection = $generator->generate($command);
            $collection->merge($resultCollection);
        }

        $fs = new Filesystem();
        foreach ($collection->getAll() as $result) {
            $fs->dumpFile($result->getFileName(), $result->getCode());
        }

        return $collection;
    }
}