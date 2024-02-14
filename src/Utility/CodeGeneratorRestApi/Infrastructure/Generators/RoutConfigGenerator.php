<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generator\RoutesConfigGenerator;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\ApplicationPathHelper;

class RoutConfigGenerator
{

    public function generate(GenerateRestApiCommand $command): GenerateResultCollection
    {
        $controllerClassName = ApplicationPathHelper::getControllerClassName($command);

        $fileName = ComposerHelper::getPsr4Path($command->getNamespace()) . '/resources/config/rest-api/v' . $command->getVersion() . '-routes.php';

//        $consoleLoadConfigGenerator = new RoutesConfigGenerator();
//        $consoleLoadConfigGenerator->generate($fileName, $controllerClassName, $command);
        $code = $this->generateConfig($fileName, $controllerClassName, $command);

        $resultCollection = new GenerateResultCollection();
        if($code) {
            $resultCollection->add(new GenerateResult($fileName, $code));
        }

//        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        return $resultCollection;
    }

    protected function generateConfig(string $configFile, string $controllerClassName, GenerateRestApiCommand $command): ?string {
        $templateFile = __DIR__ . '/../../resources/templates/route-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        $code = null;
        if(!$configGenerator->hasCode($controllerClassName)) {
            $routeName = $command->getHttpMethod() . '_' . $command->getUri();
            $controllerDefinition =
                '    $routes
        ->add(\'' . $routeName . '\', \'/' . $command->getUri() . '\')
        ->controller(\\' . $controllerClassName . '::class)
        ->methods([\'' . $command->getHttpMethod() . '\']);';
            $code = $configGenerator->appendCode($controllerDefinition);
        }
        return $code;
    }
}