<?php

namespace Untek\Utility\CodeGeneratorCli\Infrastructure\Generators;

use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Helpers\ApplicationPathHelper;
use Untek\Utility\CodeGeneratorApplication\Infrastructure\Helpers\ApplicationHelper;

class CliCommandGenerator
{

    public function generate(GenerateCliCommand $command): GenerateResult
    {
        $commandFullClassName = $command->getCommandClass();
        $commandClassName = ClassHelper::getClassOfClassName($command->getCommandClass());
        $commandClassName = Inflector::camelize($commandClassName);

        $cliCommandClassName = ApplicationPathHelper::getControllerClassName($command);

        $params = [
            'commandClassName' => $commandClassName,
            'commandFullClassName' => $commandFullClassName,
            'cliCommandName' => $command->getCliCommand(),
            'properties' => ApplicationHelper::prepareProperties($command),
        ];
        $template = __DIR__ . '/../../resources/templates/cli-command.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileName = $fileGenerator->generatePhpClass($cliCommandClassName, $template, $params);

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}