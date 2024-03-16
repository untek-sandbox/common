<?php

namespace Untek\Utility\CodeGeneratorCli\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Helpers\CliPathHelper;

class ConsoleCommandConfigGenerator
{

    private string $template = __DIR__ . '/../../resources/templates/cli-command-share-config.tpl.php';

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateCliCommand $command): void
    {
        $cliCommandClassName = CliPathHelper::getCliCommandClass($command);
        $cliCommandConfigFileName = PackageHelper::pathByNamespace($command->getNamespace()) . '/resources/config/commands.php';
        $templateFile = __DIR__ . '/../../resources/templates/cli-command-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($this->collection, $cliCommandConfigFileName, $templateFile);
        $concreteCode = '\\' . $cliCommandClassName . '';
        $codeForAppend = '  $commandConfigurator->registerCommandClass(' . $concreteCode . '::class);';
        if (!$configGenerator->hasCode($concreteCode)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $this->collection->add(new FileResult($cliCommandConfigFileName, $code));
        }
        $importResult = $this->addImport($cliCommandConfigFileName);
        if ($importResult) {
            $this->collection->add($importResult);
        }
        $this->collection->add(new FileResult($cliCommandConfigFileName, $code));
    }

    private function addImport($cliCommandConfigFileName): ?FileResult
    {
        $template = $this->template;
        $configFile = __DIR__ . '/../../../../../../../../config/console/commands.php';
        $configGenerator = new PhpConfigGenerator($this->collection, $configFile, $template);
        $shareCliCommandConfigFileName = (new Filesystem())->makePathRelative($cliCommandConfigFileName, realpath(__DIR__ . '/../../../../../../../..'));
        $shareCliCommandConfigFileName = rtrim($shareCliCommandConfigFileName, '/');
        $concreteCode = $shareCliCommandConfigFileName;
        $codeForAppend = '    $configLoader->boot(__DIR__ . \'/../../../' . $shareCliCommandConfigFileName . '\');';
        if (!$configGenerator->hasCode($concreteCode)) {
            $code = $configGenerator->appendCode($codeForAppend);
            return new FileResult($configFile, $code);
        }
        return null;
    }
}