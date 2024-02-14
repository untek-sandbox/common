<?php

namespace Untek\Utility\CodeGenerator\Infrastructure\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Untek\Core\Code\Helpers\ComposerHelper;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResult;
use Untek\Utility\CodeGeneratorApplication\Application\Dto\GenerateResultCollection;

class ContainerLoadConfigGenerator
{

    private CodeGenerator $codeGenerator;
    private Filesystem $fs;


    public function __construct(private string $namespace)
    {
        $this->codeGenerator = new CodeGenerator();
        $this->fs = new Filesystem();

    }

    public function generate(string $modulePath): GenerateResultCollection {
        $codeForAppend = '$loader->load(__DIR__ . \'/../'.$modulePath.'\');';
        $configFile = __DIR__ . '/../../../../../../../../config/container.php';
        $templateFile = __DIR__ . '/../../resources/templates/container-load-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($configFile, $templateFile);
        if(!$configGenerator->hasCode($modulePath)) {
            $code = $configGenerator->appendCode($codeForAppend . PHP_EOL);
            $this->fs->dumpFile($configFile, $code);
        }
        return new GenerateResultCollection([
            new GenerateResult($configFile, $code)
        ]);
    }

}