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
use Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generator\RoutesLoadConfigGenerator;

class RoutConfigImportGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;


    public function __construct()
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();

    }

    public function generate(GenerateRestApiCommand $command): GenerateResultCollection
    {
        $path = ComposerHelper::getPsr4Path($command->getNamespace());
        $fs = new Filesystem();
        $relative = $fs->makePathRelative($path, getenv('ROOT_DIRECTORY'));

        $modulePath = $relative . 'resources/config/rest-api/v' . $command->getVersion() . '-routes.php';

//        $consoleLoadConfigGenerator = new RoutesLoadConfigGenerator($command->getNamespace());
//        $fileName = $consoleLoadConfigGenerator->generate($modulePath, '/v' . $command->getVersion());
        $resultCollection = $this->generateConfig($modulePath, '/v' . $command->getVersion());

        foreach ($resultCollection->getAll() as $result) {
            $this->fs->dumpFile($result->getFileName(), $result->getCode());
        }
//        $fileName = GeneratorFileHelper::fileNameTotoRelative($fileName);

        return $resultCollection;
    }

    protected function generateConfig(string $modulePath, string $prefix = null): GenerateResultCollection {
        $codeForAppend = '    $routes
        ->import(__DIR__ . \'/../../../'.$modulePath.'\')
        ->prefix(\''.$prefix.'\');';
        $configFile = __DIR__ . '/../../../../../../../../context/rest-api/config/routes.php';
        $templateFile = __DIR__ . '/../../resources/templates/routes-load-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);

        $resultCollection = new GenerateResultCollection();

        if(!$configGenerator->hasCode($modulePath)) {
            $code = $configGenerator->appendCode($codeForAppend);

            $resultCollection->add(new GenerateResult($configFile, $code));
        }

        return $resultCollection;
    }
}