<?php

namespace Untek\Utility\CodeGeneratorCli\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;

class CliCommandShortcutGenerator
{

    private CodeGenerator $codeGenerator;
    private string $template = __DIR__ . '/../../resources/templates/cli-command-shortcut.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
        $this->codeGenerator = new CodeGenerator();
    }

    public function generate(GenerateCliCommand $command): void
    {
        $fileName = $this->getShortcutFileName($command);
        $params = [
            'cliCommandName' => $command->getCliCommand(),
        ];
        $template = $command->getParameter(self::class, 'template') ?: $this->template;
        $code = $this->codeGenerator->generateCode($template, $params);
        $this->collection->add(new FileResult($fileName, $code));
    }

    private function getShortcutFileName(GenerateCliCommand $command): string
    {
        $commandSections = explode(':', $command->getCliCommand());
        $moduleName = array_splice($commandSections, 0, 1)[0];
        $actionName = implode('-', $commandSections);
        $shortcutFileName = str_replace(':', '-', $command->getCliCommand());
        $binDirectory = realpath(__DIR__ . '/../../../../../../../../bin');
        $fileName = $binDirectory . '/' . $moduleName . '/' . $actionName . '.sh';
        return $fileName;
    }
}