<?php

namespace Untek\Utility\CodeGeneratorRestApi\Infrastructure\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\CodeGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\FileGenerator;
use Untek\Utility\CodeGenerator\Infrastructure\Generator\PhpConfigGenerator;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;

class RoutesLoadConfigGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;
    private FileGenerator $fileGenerator;

    public function __construct(private string $namespace)
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();
        $this->fileGenerator = new FileGenerator();
    }

    public function generate(string $modulePath, string $prefix = null): string {
        $codeForAppend = '    $routes
        ->import(__DIR__ . \'/../../../'.$modulePath.'\')
        ->prefix(\''.$prefix.'\');';
        $configFile = __DIR__ . '/../../../../../../../../context/rest-api/config/routes.php';
        $templateFile = __DIR__ . '/../../resources/templates/routes-load-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        if(!$configGenerator->hasCode($modulePath)) {
            $code = $configGenerator->appendCode($codeForAppend);
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