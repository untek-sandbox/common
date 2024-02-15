<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generators;

use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Helpers\GeneratorFileHelper;
use Untek\Utility\CodeGeneratorRestApi\Application\Commands\GenerateRestApiCommand;
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generator\RoutesLoadConfigGenerator;

class RoutConfigImportGenerator
{

    public function __construct(protected GenerateResultCollection $collection)
    {
    }

    public function generate(GenerateRestApiCommand $command): void
    {
        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        $relative = GeneratorFileHelper::fileNameTotoRelative($path);
        $modulePath = $relative . '/resources/config/rest-api/v' . $command->getVersion() . '-routes.php';
        $this->generateConfig($modulePath, '/v' . $command->getVersion());
    }

    protected function generateConfig(string $modulePath, string $prefix = null): void
    {
        $modulePath = ltrim($modulePath, '/');
        $codeForAppend = '    $routes
        ->import(__DIR__ . \'/../../../' . $modulePath . '\')
        ->prefix(\'' . $prefix . '\');';
        $configFile = __DIR__ . '/../../../../../../../../context/rest-api/config/routes.php';
        $templateFile = __DIR__ . '/../../resources/templates/routes-load-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($this->collection, $configFile, $templateFile);
        if (!$configGenerator->hasCode($modulePath)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $this->collection->add(new FileResult($configFile, $code));
        }
    }
}