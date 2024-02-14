<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generator\RoutesConfigGenerator;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\ApplicationPathHelper;

class RoutConfigGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;
    private FileGenerator $fileGenerator;

    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();
        $this->fileGenerator = new FileGenerator();
    }

    public function generate(GenerateRestApiCommand $command): GenerateResult
    {
        $controllerClassName = ApplicationPathHelper::getControllerClassName($command);

        $fileName = ComposerHelper::getPsr4Path($command->getNamespace()) . '/resources/config/rest-api/v' . $command->getVersion() . '-routes.php';

//        $consoleLoadConfigGenerator = new RoutesConfigGenerator();
//        $consoleLoadConfigGenerator->generate($fileName, $controllerClassName, $command);
        $this->generateConfig($fileName, $controllerClassName, $command);

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }

    protected function generateConfig(string $configFile, string $controllerClassName, GenerateRestApiCommand $command): string {
        $templateFile = __DIR__ . '/../../resources/templates/route-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        if(!$configGenerator->hasCode($controllerClassName)) {
            $routeName = $command->getHttpMethod() . '_' . $command->getUri();
            $controllerDefinition =
                '    $routes
        ->add(\'' . $routeName . '\', \'/' . $command->getUri() . '\')
        ->controller(\\' . $controllerClassName . '::class)
        ->methods([\'' . $command->getHttpMethod() . '\']);';
            $code = $configGenerator->appendCode($controllerDefinition);
            $this->dump($configFile, $code);
        }
        return $configFile;
    }

    protected function dump(string $fileName, string $code): GenerateResult
    {
        $this->fs->dumpFile($fileName, $code);
        $generateResult = new GenerateResult($fileName, $code);
        return $generateResult;
    }
}