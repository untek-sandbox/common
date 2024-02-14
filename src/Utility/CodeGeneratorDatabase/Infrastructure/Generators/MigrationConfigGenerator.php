<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;

class MigrationConfigGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;

    public function __construct(private string $namespace, private string $migrationConfigFile)
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();
    }

    public function generate(): GenerateResultCollection
    {
        $fileName = PackageHelper::pathByNamespace($this->namespace) . '/resources/migrations';
        $fileName = (new Filesystem())->makePathRelative($fileName, realpath(__DIR__ . '/../../../../../../../..'));
        $fileName = rtrim($fileName, '/');

        $concreteCode = $fileName;
        $codeForAppend = "    __DIR__ . '/../$fileName',";

        $configFile = $this->migrationConfigFile;
        $templateFile = __DIR__ . '/../../resources/templates/migration-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);

        $resultCollection = new GenerateResultCollection();
        if(!$configGenerator->hasCode($concreteCode)) {
            $code = $configGenerator->appendCode($codeForAppend);
            $resultCollection->add(new GenerateResult($configFile, $code));
        }

        return $resultCollection;
    }
}