<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generator\RoutesLoadConfigGenerator;

class RoutConfigImportGenerator
{

    public function generate(GenerateRestApiCommand $command): GenerateResultCollection
    {
        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        $relative = GeneratorFileHelper::fileNameTotoRelative($path);
        $modulePath = $relative . '/resources/config/rest-api/v' . $command->getVersion() . '-routes.php';
        $resultCollection = $this->generateConfig($modulePath, '/v' . $command->getVersion());
        return $resultCollection;
    }

    protected function generateConfig(string $modulePath, string $prefix = null): GenerateResultCollection
    {
        $modulePath = ltrim($modulePath, '/');
        $codeForAppend = '    $routes
        ->import(__DIR__ . \'/../../../' . $modulePath . '\')
        ->prefix(\'' . $prefix . '\');';
        $configFile = __DIR__ . '/../../../../../../../../context/rest-api/config/routes.php';
        $templateFile = __DIR__ . '/../../resources/templates/routes-load-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        $resultCollection = new GenerateResultCollection();
        if (!$configGenerator->hasCode($modulePath)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $resultCollection->add(new GenerateResult($configFile, $code));
        }
        return $resultCollection;
    }
}