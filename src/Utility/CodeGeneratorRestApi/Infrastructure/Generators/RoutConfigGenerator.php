<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generator\RoutesConfigGenerator;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Helpers\ApplicationPathHelper;

class RoutConfigGenerator
{

    public function generate(GenerateRestApiCommand $command): GenerateResult
    {
        $controllerClassName = ApplicationPathHelper::getControllerClassName($command);

        $fileName = ComposerHelper::getPsr4Path($command->getNamespace()) . '/resources/config/rest-api/v' . $command->getVersion() . '-routes.php';

        $consoleLoadConfigGenerator = new RoutesConfigGenerator();
        $consoleLoadConfigGenerator->generate($fileName, $controllerClassName, $command);

        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        $generateResult = new GenerateResult();
        $generateResult->setFileName($fileName);
        return $generateResult;
    }
}