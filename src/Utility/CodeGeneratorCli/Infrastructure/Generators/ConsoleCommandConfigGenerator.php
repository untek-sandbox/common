<?php

namespace Untek\Utility\CodeGeneratorCli\Infrastructure\Generators;

use Untek\Utility\CodeGeneratorCli\Application\Commands\GenerateCliCommand;
use Untek\Utility\CodeGeneratorCli\Infrastructure\Helpers\ApplicationPathHelper;
use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;

class ConsoleCommandConfigGenerator
{

    public function generate(GenerateCliCommand $command): GenerateResult
    {
        $cliCommandClassName = ApplicationPathHelper::getControllerClassName($command);
        $cliCommandConfigFileName = PackageHelper::pathByNamespace($command->getNamespace()) . '/resources/config/commands.php';

        $templateFile = __DIR__ . '/../../resources/templates/cli-command-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($cliCommandConfigFileName, $templateFile);
        $concreteCode = '\\'.$cliCommandClassName.'';
        $codeForAppend = '  $commandConfigurator->registerCommandClass('.$concreteCode.'::class);';
        if(!$configGenerator->hasCode($concreteCode)) {
            $configGenerator->appendCode($codeForAppend);
        }
        $this->addImport($cliCommandConfigFileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($cliCommandConfigFileName);
        return $generateResult;
    }

    private function addImport($cliCommandConfigFileName) {
        $templateFile = __DIR__ . '/../../resources/templates/cli-command-share-config.tpl.php';
        $configGenerator = new PhpConfigGenerator(__DIR__ . '/../../../../../../../../context/console/config/commands.php', $templateFile);
        $shareCliCommandConfigFileName = (new Filesystem())->makePathRelative($cliCommandConfigFileName, realpath(__DIR__ . '/../../../../../../../..'));
        $shareCliCommandConfigFileName = rtrim($shareCliCommandConfigFileName, '/');

        $concreteCode = $shareCliCommandConfigFileName;
        $codeForAppend = '    $configLoader->boot(__DIR__ . \'/../../../'.$shareCliCommandConfigFileName.'\');';
        if(!$configGenerator->hasCode($concreteCode)) {
            $configGenerator->appendCode($codeForAppend);
        }
    }
}