<?php

namespace Untek\Utility\CodeGeneratorCli\Application\Handlers;

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

        $files = [];

        $resultCollection = (new CliCommandGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new ConsoleCommandConfigGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new ContainerConfigGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        $resultCollection = (new CliCommandShortcutGenerator())->generate($command);
        foreach ($resultCollection->getAll() as $result) {
            $files[] = $result->getFileName();
        }

        return $files;
    }
}