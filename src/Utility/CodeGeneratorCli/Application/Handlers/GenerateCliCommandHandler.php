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

        $result = (new CliCommandGenerator())->generate($command);
        $files[] = $result->getFileName();

        $result = (new ConsoleCommandConfigGenerator())->generate($command);
        $files[] = $result->getFileName();

        $result = (new ContainerConfigGenerator())->generate($command);
        $files[] = $result->getFileName();

        $result = (new CliCommandShortcutGenerator())->generate($command);
        $files[] = $result->getFileName();

        return $files;
    }
}