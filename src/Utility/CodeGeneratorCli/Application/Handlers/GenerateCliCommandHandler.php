<?php

namespace Untek\Utility\CodeGeneratorCli\Application\Handlers;

use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorHelper;
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

        $generators = [
            new CliCommandGenerator(),
            new ConsoleCommandConfigGenerator(),
            new ContainerConfigGenerator(),
            new CliCommandShortcutGenerator(),
        ];

        $collection = GeneratorHelper::generate($generators, $command);
        GeneratorHelper::dump($collection);

        return $collection;
    }
}