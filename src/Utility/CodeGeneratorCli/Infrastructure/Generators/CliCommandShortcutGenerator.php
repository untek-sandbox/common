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

class CliCommandShortcutGenerator
{

    public function generate(GenerateCliCommand $command): GenerateResult
    {
        $fileName = $this->getShortcutFileName($command);
        $params = [
            'cliCommandName' => $command->getCliCommand(),
        ];
        $template = __DIR__ . '/../../resources/templates/cli-command-shortcut.tpl.php';

        $fileGenerator = new FileGenerator();
        $fileGenerator->generateFile($fileName, $template, $params);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }

    private function getShortcutFileName(GenerateCliCommand $command): string {
        $lowerModuleName = mb_strtolower($command->getModuleName());
        $commandSections = explode(':', $command->getCliCommand());
        $moduleName = array_splice($commandSections, 0, 1)[0];
        $actionName = implode('-', $commandSections);
//        dd($moduleName, $actionName);

        $shortcutFileName = str_replace(':', '-', $command->getCliCommand());
        $binDirectory = realpath(__DIR__ . '/../../../../../../../../bin');
        $fileName = $binDirectory . '/' . $lowerModuleName . '/' . $actionName . '.sh';
        return $fileName;
    }
}