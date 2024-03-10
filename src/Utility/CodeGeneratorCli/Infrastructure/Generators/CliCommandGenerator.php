<?php

namespace Untek\Utility\CodeGeneratorCli\Infrastructure\Generators;

use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;
use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Helpers\CliPathHelper;

class CliCommandGenerator
{

    private CodeGenerator $codeGenerator;

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateCliCommand $command): void
    {
        $commandFullClassName = $command->getCommandClass();
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $commandClassName = Inflector::camelize($commandClassName);
        $cliCommandClassName = CliPathHelper::getCliCommandClass($command);
        $params = [
            'commandClassName' => $commandClassName,
            'commandFullClassName' => $commandFullClassName,
            'cliCommandName' => $command->getCliCommand(),
            'properties' => ApplicationHelper::prepareProperties($command),
        ];
        $template = __DIR__ . '/../../resources/templates/cli-command.tpl.php';
        $fileName = GeneratorFileHelper::getFileNameByClass($cliCommandClassName);
        $code = $this->codeGenerator->generatePhpClassCode($cliCommandClassName, $template, $params);
        $this->collection->add(new FileResult($fileName, $code));
    }
}