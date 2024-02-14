<?php

namespace Untek\Utility\CodeGeneratorCli\Infrastructure\Generators;

use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
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

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;


    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();

    }

    public function generate(GenerateCliCommand $command): GenerateResult
    {
        $cliCommandClassName = ApplicationPathHelper::getControllerClassName($command);
        $cliCommandConfigFileName = PackageHelper::pathByNamespace($command->getNamespace()) . '/resources/config/commands.php';

        $templateFile = __DIR__ . '/../../resources/templates/cli-command-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($cliCommandConfigFileName, $templateFile);
        $concreteCode = '\\'.$cliCommandClassName.'';
        $codeForAppend = '  $commandConfigurator->registerCommandClass('.$concreteCode.'::class);';
        if(!$configGenerator->hasCode($concreteCode)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $this->fs->dumpFile($cliCommandConfigFileName, $code);
        }
        $importResult = $this->addImport($cliCommandConfigFileName);

        return new GenerateResult($cliCommandConfigFileName, $code);
    }

    private function addImport($cliCommandConfigFileName) {
        $templateFile = __DIR__ . '/../../resources/templates/cli-command-share-config.tpl.php';
        $configFile = __DIR__ . '/../../../../../../../../context/console/config/commands.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        $shareCliCommandConfigFileName = (new Filesystem())->makePathRelative($cliCommandConfigFileName, realpath(__DIR__ . '/../../../../../../../..'));
        $shareCliCommandConfigFileName = rtrim($shareCliCommandConfigFileName, '/');

        $concreteCode = $shareCliCommandConfigFileName;
        $codeForAppend = '    $configLoader->boot(__DIR__ . \'/../../../'.$shareCliCommandConfigFileName.'\');';
        if(!$configGenerator->hasCode($concreteCode)) {
            $code = $configGenerator->appendCode($codeForAppend);

            $this->fs->dumpFile($configFile, $code);
            return new GenerateResult($configFile, $code);
        }
    }

}