<?php

namespace Untek\Utility\CodeGeneratorCli\Application\Handlers;

use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Application\Dto\InfoResult;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Generators\ContainerConfigImportGenerator;
use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;
use Untek\Utility\CodeGeneratorCli\Application\Validators\GenerateCliCommandValidator;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Generators\CliCommandGenerator;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Generators\CliCommandShortcutGenerator;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Generators\ConsoleCommandConfigGenerator;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Generators\ContainerConfigGenerator;

class GenerateCliCommandHandler
{

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    /**
     * @param GenerateCliCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(GenerateCliCommand $command)
    {
        $validator = new GenerateCliCommandValidator();
        $validator->validate($command);

        $generators = [
            new CliCommandGenerator($this->collection),
//            new ConsoleCommandConfigGenerator($this->collection),
            new ContainerConfigGenerator($this->collection),
            new ContainerConfigImportGenerator($this->collection, '/resources/config/services/console.php', __DIR__ . '/../../../../../../../../config/console/container.php'),
            new CliCommandShortcutGenerator($this->collection),
        ];

        GeneratorHelper::generate($generators, $command);
//        GeneratorHelper::dump($this->collection);

        $cliCommand = $command->getCliCommand();
        $this->collection->add(new InfoResult('CLI command', 'php bin/console ' . $cliCommand));
    }
}