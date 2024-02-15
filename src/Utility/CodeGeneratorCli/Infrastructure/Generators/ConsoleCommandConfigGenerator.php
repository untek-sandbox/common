<?php

namespace Untek\Utility\CodeGeneratorCli\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Helpers\ApplicationPathHelper;

class ConsoleCommandConfigGenerator
{

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateCliCommand $command): GenerateResultCollection
    {
        $cliCommandClassName = ApplicationPathHelper::getControllerClassName($command);
        $cliCommandConfigFileName = PackageHelper::pathByNamespace($command->getNamespace()) . '/resources/config/commands.php';
        $templateFile = __DIR__ . '/../../resources/templates/cli-command-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($cliCommandConfigFileName, $templateFile);
        $concreteCode = '\\' . $cliCommandClassName . '';
        $codeForAppend = '  $commandConfigurator->registerCommandClass(' . $concreteCode . '::class);';
        $resultCollection = new GenerateResultCollection();
        if (!$configGenerator->hasCode($concreteCode)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $this->collection->add(new FileResult($cliCommandConfigFileName, $code));
        }
        $importResult = $this->addImport($cliCommandConfigFileName);
        if ($importResult) {
            $this->collection->add($importResult);
        }
        $this->collection->add(new FileResult($cliCommandConfigFileName, $code));
        return $resultCollection;
    }

    private function addImport($cliCommandConfigFileName): ?FileResult
    {
        $templateFile = __DIR__ . '/../../resources/templates/cli-command-share-config.tpl.php';
        $configFile = __DIR__ . '/../../../../../../../../context/console/config/commands.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        $shareCliCommandConfigFileName = (new Filesystem())->makePathRelative($cliCommandConfigFileName, realpath(__DIR__ . '/../../../../../../../..'));
        $shareCliCommandConfigFileName = rtrim($shareCliCommandConfigFileName, '/');
        $concreteCode = $shareCliCommandConfigFileName;
        $codeForAppend = '    $configLoader->boot(__DIR__ . \'/../../../' . $shareCliCommandConfigFileName . '\');';
        if (!$configGenerator->hasCode($concreteCode)) {
            $code = $configGenerator->appendCode($codeForAppend);
            return new FileResult($configFile, $code);
        }
    }
}