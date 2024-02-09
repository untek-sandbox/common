<?php

namespace Untek\Utility\CodeGeneratorDatabase\Infrastructure\Generators;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Core\Code\Helpers\PackageHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;

class MigrationConfigGenerator
{

    public function __construct(private string $namespace, private string $migrationConfigFile)
    {
    }

    public function generate(): string {
        $fileName = PackageHelper::pathByNamespace($this->namespace) . '/resources/migrations';
        $fileName = (new Filesystem())->makePathRelative($fileName, realpath(__DIR__ . '/../../../../../../../..'));
        $fileName = rtrim($fileName, '/');

        $concreteCode = $fileName;
        $codeForAppend = "    __DIR__ . '/../$fileName',";

        $configFile = $this->migrationConfigFile;
        $templateFile = __DIR__ . '/../../resources/templates/migration-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        if(!$configGenerator->hasCode($concreteCode)) {
            $configGenerator->appendCode($codeForAppend);
        }
        return $configFile;
    }
}