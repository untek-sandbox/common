<?php

namespace Untek\Utility\CodeGeneratorCli\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;

class CliCommandShortcutGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;


    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();

    }

    public function generate(GenerateCliCommand $command): GenerateResult
    {
        $fileName = $this->getShortcutFileName($command);
        $params = [
            'cliCommandName' => $command->getCliCommand(),
        ];
        $template = __DIR__ . '/../../resources/templates/cli-command-shortcut.tpl.php';

        $code = $this->codeGenerator->generateCode($template, $params);

        $this->fs->dumpFile($fileName, $code);
        return new GenerateResult($fileName, $code);
    }

    private function getShortcutFileName(GenerateCliCommand $command): string
    {
//        $lowerModuleName = mb_strtolower($command->getModuleName());
        $commandSections = explode(':', $command->getCliCommand());
        $moduleName = array_splice($commandSections, 0, 1)[0];
        $actionName = implode('-', $commandSections);

        $shortcutFileName = str_replace(':', '-', $command->getCliCommand());
        $binDirectory = realpath(__DIR__ . '/../../../../../../../../bin');
        $fileName = $binDirectory . '/' . $moduleName . '/' . $actionName . '.sh';
        return $fileName;
    }
}