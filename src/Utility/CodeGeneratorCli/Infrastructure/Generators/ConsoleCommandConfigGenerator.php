<?php

namespace Untek\Utility\CodeGeneratorCli\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Helpers\ApplicationPathHelper;

class ConsoleCommandConfigGenerator
{

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
            $resultCollection->add(new GenerateResult($cliCommandConfigFileName, $code));
        }
        $importResult = $this->addImport($cliCommandConfigFileName);
        if ($importResult) {
            $resultCollection->add($importResult);
        }
        $resultCollection->add(new GenerateResult($cliCommandConfigFileName, $code));
        return $resultCollection;
    }

    private function addImport($cliCommandConfigFileName): ?GenerateResult
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
            return new GenerateResult($configFile, $code);
        }
    }
}